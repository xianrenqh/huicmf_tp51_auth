<?php
/********************************************
 * MODULE:FTP类
 *******************************************/

namespace lib;

class FtpLib
{

    public $off;             // 返回操作状态(成功/失败)

    public $conn_id;           // FTP连接

    protected $ftp_host;

    protected $ftp_port;

    protected $ftp_user;

    protected $ftp_pwd;

    /**
     * 方法：FTP连接
     * @FTP_HOST -- FTP主机
     * @FTP_PORT -- 端口
     * @FTP_USER -- 用户名
     * @FTP_PASS -- 密码
     */
    public function __construct()
    {
        $this->ftp_host = get_config('ftp_host');
        $this->ftp_port = get_config('ftp_port');
        $this->ftp_user = get_config('ftp_user');
        $this->ftp_pwd  = get_config('ftp_pwd');
    }

    function connect()
    {
        if ( ! function_exists('ftp_connect')) {
            return -3;
        } else {
            $this->conn_id = @ftp_connect($this->ftp_host, $this->ftp_port, 90);
            if ( ! $this->conn_id) {
                return -1;
            }
            if ( ! @ftp_login($this->conn_id, $this->ftp_user, $this->ftp_pwd)) {
                return -2;
            } else {
                return 1;
            }
        }
    }

    /**
     * 方法：上传文件
     * @path    -- 本地路径
     * @newpath -- 上传路径
     * @type    -- 若目标目录不存在则新建
     */
    function up_file($path, $newpath, $type = true)
    {
        if ($type) {
            $this->dir_mkdirs($newpath);
        }
        $this->off = @ftp_put($this->conn_id, $newpath, $path, FTP_BINARY);
        if ( ! $this->off) {
            return ['code' => 1001, 'msg' => '文件上传失败,请检查权限及路径是否正确！'];
        } else {
            return ['code' => 1, 'msg' => '上传成功'];
        }
    }

    /**
     * 方法：移动文件
     * @path    -- 原路径
     * @newpath -- 新路径
     * @type    -- 若目标目录不存在则新建
     */
    function move_file($path, $newpath, $type = true)
    {
        if ($type) {
            $this->dir_mkdirs($newpath);
        }
        $this->off = @ftp_rename($this->conn_id, $path, $newpath);
        if ( ! $this->off) {
            echo "文件移动失败,请检查权限及原路径是否正确！";
        }
    }

    /**
     * 方法：复制文件
     * 说明：由于FTP无复制命令,本方法变通操作为：下载后再上传到新的路径
     * @path    -- 原路径
     * @newpath -- 新路径
     * @type    -- 若目标目录不存在则新建
     */
    function copy_file($path, $newpath, $type = true)
    {
        $downpath  = "c:/tmp.dat";
        $this->off = @ftp_get($this->conn_id, $downpath, $path, FTP_BINARY);// 下载
        if ( ! $this->off) {
            echo "文件复制失败,请检查权限及原路径是否正确！";
        }
        $this->up_file($downpath, $newpath, $type);
    }

    /**
     * 方法：删除文件
     * @path -- 路径
     */
    function del_file($path)
    {
        $this->off = @ftp_delete($this->conn_id, $path);
        if ( ! $this->off) {
            echo "文件删除失败,请检查权限及路径是否正确！";
        }
    }

    /**
     * 方法：生成目录
     * @path -- 路径
     */
    function dir_mkdirs($path)
    {
        $path_arr  = explode('/', $path);       // 取目录数组
        $file_name = array_pop($path_arr);      // 弹出文件名
        $path_div  = count($path_arr);        // 取层数

        foreach ($path_arr as $val)          // 创建目录
        {
            if (@ftp_chdir($this->conn_id, $val) == false) {
                $tmp = @ftp_mkdir($this->conn_id, $val);
                if ($tmp == false) {
                    echo "目录创建失败,请检查权限及路径是否正确！";
                    exit;
                }
                @ftp_chdir($this->conn_id, $val);
            }
        }

        for ($i = 1; $i <= $path_div; $i++)         // 回退到根
        {
            @ftp_cdup($this->conn_id);
        }
    }

    /**
     * 方法：关闭FTP连接
     */
    function close()
    {
        @ftp_close($this->conn_id);
    }
}
// class class_ftp end
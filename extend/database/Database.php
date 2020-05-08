<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace database;

//数据导出模型
class Database
{

    /**
     * 文件指针
     * @var resource
     */
    private $fp;

    /**
     * 备份文件信息 part - 卷号，name - 文件名,dbname-数据库名
     * @var array
     */
    private $file;

    /**
     * 还原文件信息
     * @var string
     */
    private $importFile;

    /**
     * 当前打开文件大小
     * @var integer
     */
    private $size = 0;

    /**
     * 备份配置
     * @var array
     */
    private $config;

    /**
     * 数据库操作类链接
     * @var connection
     */
    private $db;

    /**
     * 备份文件生成文件名及对应数据库名
     * @var integer
     */
    private $backupFile = array();

    /**
     * 错误信息
     * @var string
     */
    private $errorMess;

    /**
     * 数据库备份构造方法
     *
     * @param array  $file   备份或还原的文件信息  $file = array('name' => 备份文件名,'part' => 1,'dbname'=>'');
     * @param array  $config 备份配置信息 array('compress'=>'是否压缩 1压缩 0不压缩','path'=>'备份地址','level'=>'压缩等级')
     * @param object $conn   数据库链接
     */
    public function __construct($file, $config, $conn)
    {
        $this->file   = $file;
        $this->config = $config;
        $this->db     = $conn;
    }

    /*
     * 手动自增
     */
    public function setFile()
    {
        $this->file['part']++; //压缩包券号
        $this->fp = null;
    }

    /*
     * 设置压缩文件对应的数据库名称
     * @param string $dbname 数据库名称(别名)
     */
    public function setFileDbname($dbname)
    {
        $this->file['dbname'] = $dbname;
    }

    /*
     * 重新设置数据库链接
     */
    public function setDb($conn)
    {
        $this->db = $conn;
    }

    /*
     * 设置还原文件信息
     */
    public function setImportFile($file)
    {
        $this->importFile = $file;
    }

    /*
     * 获取生成备份文件名称及对应数据库
     */
    public function getBackupFile()
    {
        return $this->backupFile;
    }

    /*
     * 获取错误信息
     */
    public function getErrorMess()
    {
        return $this->errorMess;
    }

    /**
     * 打开一个卷，用于写入数据
     *
     * @param integer $size 写入数据的大小
     */
    private function open($size)
    {
        if ($this->fp) {
            $this->size += $size;
            if ($this->size > $this->config['part']) { //压缩文件大小
                $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);//是否压缩
                $this->fp = null;
                $this->file['part']++;
                $this->create();
            }
        } else {
            $backuppath = $this->config['path'];
            $filename   = "{$backuppath}{$this->file['name']}-{$this->file['part']}.sql";
            if ($this->config['compress']) {
                $filename = "{$filename}.gz";
                $this->fp = @gzopen($filename, "a{$this->config['level']}");
            } else {
                $this->fp = @fopen($filename, 'a');
            }
            $this->size       = filesize($filename) + $size;
            $file             = basename($filename);
            $database         = $this->file['dbname'];
            $this->backupFile = array_merge($this->backupFile, array($file => $database));
        }
    }

    /**
     * 写入初始数据
     * @return boolean true - 写入成功，false - 写入失败
     */
    public function create()
    {
        $sql = "-- -----------------------------\n";
        $sql .= "-- Think MySQL Data Transfer \n";
        $sql .= "-- \n";
        $sql .= "-- Database : ".$this->file['dbname']."\n";
        $sql .= "-- \n";
        $sql .= "-- Part : #{$this->file['part']}\n";
        $sql .= "-- Date : ".date("Y-m-d H:i:s")."\n";
        $sql .= "-- -----------------------------\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        return $this->write($sql);
    }

    /**
     * 写入SQL语句
     *
     * @param string $sql 要写入的SQL语句
     *
     * @return boolean     true - 写入成功，false - 写入失败！
     */
    private function write($sql)
    {
        $size = strlen($sql);
        //由于压缩原因，无法计算出压缩后的长度，这里假设压缩率为50%，
        //一般情况压缩率都会高于50%；
        $size = $this->config['compress'] ? $size / 2 : $size;
        $this->open($size);

        return $this->config['compress'] ? @gzwrite($this->fp, $sql) : @fwrite($this->fp, $sql);
    }

    /**
     * 备份表结构
     *
     * @param string  $table 表名
     * @param integer $start 起始行数
     * @param string  $dblink
     *
     * @return boolean        false - 备份失败
     */
    public function backup($table, $start)
    {
        //备份表结构
        if (0 == $start) {
            $result = $this->db->query("SHOW CREATE TABLE `{$table}`");
            $sql    = "\n";
            $sql    .= "-- -----------------------------\n";
            $sql    .= "-- Table structure for `{$table}`\n";
            $sql    .= "-- -----------------------------\n";
            $sql    .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql    .= trim($result[0]['Create Table']).";\n\n";
            if (false === $this->write($sql)) {
                $this->errorMess = $sql."备份失败!";

                return false;
            }
        }
        $result = $this->db->query("SELECT COUNT(*) AS count FROM `{$table}`");
        //数据总数
        $count = $result['0']['count'];
        //备份表数据
        if ($count) {
            //写入数据注释
            if (0 == $start) {
                $sql = "-- -----------------------------\n";
                $sql .= "-- Records of `{$table}`\n";
                $sql .= "-- -----------------------------\n";
                $this->write($sql);
            }
            //备份数据记录
            $result = $this->db->query("SELECT * FROM `{$table}` LIMIT {$start}, 1000");
            foreach ($result as $row) {
                $row = array_map([$this, 'safeData'], $row);
                $row = "'".implode("','", $row)."'";
                $row = str_replace("''", "'0'", $row);
                $sql = "INSERT INTO `{$table}` VALUES (".$row.");\n";
                if (false === $this->write($sql)) {
                    return false;
                }
            }
            //还有更多数据
            if ($count > $start + 1000) {
                return $start + 1000;
            }
        }

        //备份下一表
        return 0;
    }

    /*
     * 过滤数据字段值
     */
    private function safeData($value)
    {
        $value = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a", " "),
            array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z', "0"), $value);

        return $value;
    }

    /*
     * 数据还原
     * @param int $start 读取文件指针位置
     */
    public function import($start)
    {
        if ($this->config['compress']) {
            $gz = gzopen($this->importFile, 'r');
        } else {
            $gz = fopen($this->importFile, 'r');
        }
        $sql = '';
        if ($start) {
            $this->config['compress'] ? gzseek($gz, $start) : fseek($gz, $start);
        }
        for ($i = 0; $i < 1000; $i++) {
            $sql .= $this->config['compress'] ? gzgets($gz) : fgets($gz);
            dump($sql);
            if (preg_match('/.*;$/', trim($sql))) {
                try {
                    dump($sql);
                    exit;
                    if (false !== $this->db->query($sql)) {
                        $start += strlen($sql);
                    } else {
                        $this->errorMess = $sql."执行失败";

                        return false;
                    }
                } catch (\Exception $e) {
                    $this->errorMess = $e->getMessage();

                    return false;
                }
                $sql = '';
            } elseif ($this->config['compress'] ? gzeof($gz) : feof($gz)) {
                return 0;
            }
        }

        return $start;
    }

    /**
     * 析构方法，用于关闭文件资源
     */
    public function __destruct()
    {
        $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);
    }
}
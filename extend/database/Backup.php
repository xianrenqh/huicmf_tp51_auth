<?php

namespace database;
use think\Db;
use app\admin\library\ExtraService;

/*
 * 数据库备份、修复、优化、还原
 */

class Backup
{
    //当前执行数据库
    private $exportDbname = array();
    private $dataBase; //数据库操作类
    private $defDatabase; //默认数据库名称

    public function __construct()
    {
        $this->defDatabase = config('database.database');
    }

    /*
     * 执行备份或还原时检测是否存在锁文件
     */
    private function creatLock()
    {
        $path = config('data_backup_path');
        $lock = $path . "backup.lock";
        if (is_file($lock)) {
            return false;
        } else {
            file_put_contents($lock, time());
            return $lock;
        }
    }

    /*
     *  获取需要备份数据表
     *  return array
     */
    public function getTableList()
    {
        $list = Db::query('SHOW TABLE STATUS');
        $tablelist = array();
        $list = array_map('array_change_key_case', $list);
        //默认数据库名称
        foreach ($list as $val) {
            $val['dbname'] = $this->defDatabase;
            $tablelist[] = $val;
        }
        $dblist = config('dbconfig.');
        foreach ($dblist as $key => $val) {
            //只处理需要备份的数据库，第三方只引用数据的库不做任何处理
            if (isset($val['isback']) && $val['isback'] == 1) {
                $list = Db::connect($val['dblink'])->query('SHOW TABLE STATUS');
                $list = array_map('array_change_key_case', $list);
                foreach ($list as $k => $v) {
                    $v['dbname'] = $key;
                    $tablelist[] = $v;
                }
            }
        }
        return $tablelist;
    }

    /*
     * 初始化备份方法
     */
    private function initExportTable()
    {
        //读取备份配置
        $config = [
            'path' => config('data_backup_path'),
            'part' => config('data_backup_path_size'),  //数据库备份卷大小
            'compress' => config('data_backup_compress'), //是否压缩
            'level' => config('data_backup_compress_level'),//数据库备份文件压缩级别 1:普通4:一般9:最高
        ];
        //检查备份目录是否可写
        if (!is_writeable($config['path'])) {
            return array('result' => 0, 'msg' => $config['path'] . '备份目录不存在或不可写，请检查后重试！');
        }
        //生成备份文件信息
        $file = array(
            'name' => date('Ymd-His', time()),
            'part' => 1,
            'dbname' => ''
        );
        return array('result' => 1, 'file' => $file, 'config' => $config);
    }

    /*
      * 开始备份
      * @param array $table 要备份的表
      * @parma int $start 开始行数（分页查询）
      */
    private function exportTable($table, $start)
    {
        $dbname = $table['dbname']; //数据库名
        if ($dbname != $this->defDatabase) {
            $dblink = config('dbconfig.' . $dbname); //其他数据库链接参数
            if (!in_array($dbname, $this->exportDbname)) {
                array_push($this->exportDbname, $dbname);
                $this->dataBase->setFileDbname($dbname);
                $this->dataBase->setDb(Db::connect($dblink));
                $this->dataBase->setFile(); //新增的库则新建压缩包
                $this->dataBase->create();
            }
        }
        //开始备份
        $start = $this->dataBase->backup($table['name'], $start);
        if ($start > 0) {
            $start = $this->exportTable($table, $start);
        }
        return $start;
    }

    /*
	 * 系统备份
	 * @param int $auto 0 收到备份 1 自动备份
	 */
    public function systemExport($auto = 0)
    {
        //获取所有备份表
        $tablelist = $this->getTableList();
        //初始化参数
        $initinfo = $this->initExportTable();
        if ($initinfo['result'] > 0) {
            //检查是否有正在执行的任务
            $lock = $this->creatLock();
            if ($lock === false) {
                return array('result' => 0, 'msg' => '检测到有一个备份任务正在执行，请稍后再试！');
            }
            $this->exportDbname = array($this->defDatabase);
            //系统自动备份和手动备份不在一起
            if ($auto == 1) {
                $initinfo['config']['path'] = $initinfo['config']['path'] . "/" . date('Ymd') . "/";
                if (!is_dir($initinfo['config']['path'])) {
                    mkdir($initinfo['config']['path'], 0777, true);
                }
            }
            $conn = Db::connect();
            $this->dataBase = new \database\Database($initinfo['file'], $initinfo['config'], $conn);
            $result = 1;
            //开始循环备份表
            foreach ($tablelist as $k => $val) {
                $start = $this->exportTable($val, 0);
                if ($start === false) {
                    //备份失败，删除锁，返回错误信息
                    unlink($lock);
                    return array('result' => 0, 'msg' => $this->dataBase->getErrorMess());
                    break;
                }
            }
            $this->exportDbname = array();
            unlink($lock);
            //获取生成备份文件及对应目录
            $backup = $this->dataBase->getBackupFile();
            $filearr['file_name'] = "backup";
            $filearr['file_content'] = array_merge(config('backup.'), $backup);
            ExtraService::writeConfig($filearr);
            return array('result' => 1, 'msg' => '备份成功！');
        } else {
            return $initinfo;
        }
    }

    /*
     * 优化表/修复表
     * @param string $type REPAIR/OPTIMIZE
     */
    public function handTable($type)
    {
        $tablelist = $this->getTableList();
        foreach ($tablelist as $k => $val) {
            if ($val['dbname'] == $this->defDatabase) {
                Db::query($type . " TABLE `{$val['name']}`");
            } else {
                $dblink = config('dbconfig.' . $val['dbname']);
                if ($dblink['type'] == 'mysql') {
                    Db::connect($dblink['dblink'])->query($type . " TABLE `{$val['name']}`");
                }
            }
        }
    }

    /*
     * 还原表
     */
    private function importTable($start)
    {
        $start = $this->dataBase->import($start);
        if (false === $start) {
            return false;
        } elseif ($start > 0) {
            $start = $this->importTable($start);
        }
        return $start;
    }

    /**
     * 初始化还原数据库
     * @param  int $time 时间戳格式 1544170524
     * return array $list array([1]=>array([0]=>1,[1]=>D:\htdocs\data/20181207-161524-1.sql.gz,[2]=>1,[3]=>mysql://root:roor@127.0.0.1:3306/dbname),[2]=>array([0]=>2,[1]=>D:\htdocs\data/20181207-161524-2.sql.gz,[2]=>1,[3]=>mysql://root:roor@127.0.0.1:3306/dbname)...)
     */
    private function initImport($time)
    {
        $name = date('Ymd-His', $time) . '-*.sql*';
        $path = config('data_backup_path') . $name;
        $files = glob($path);
        $list = array();
        $data = config('backup.');
        foreach ($files as $name) {
            $basename = basename($name);
            $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
            $gz = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
            $dbname = isset($data[$basename]) ? $data[$basename] : "";
            $dblink = $dbname ? config('dbconfig.' . $dbname) : array("dblink" => "");
            $list[$match[6]] = array($match[6], $name, $gz, $dblink['dblink']);
        }
        return $list;
    }

    /*
         * 还原数据库
         */
    public function import($time)
    {
        $list = $this->initImport($time);
        if ($list) {
            $lock = $this->creatLock();
            if ($lock === false) {
                return array('result' => 0, 'msg' => '检测到有一个还原任务正在执行，请稍后再试！');
            }
            if (!isset($list[1][2])) {
                return array('result' => 0, 'msg' => '检测不到备份文件格式！');
            }
            $config = array('path' => config('data_backup_path'), 'compress' => $list[1][2]);
            $conn = Db::connect();
            $this->dataBase = new \database\Database(array(), $config, $conn);
            foreach ($list as $val) {
                $this->dataBase->setImportFile($val[1]);
                //$val[3] 是数据库链接字符串，空为链接默认数据库
                if ($val[3]) {
                    $this->dataBase->setDb(Db::connect($val[3]));
                }
                $result = $this->importTable(0);
                if ($result === false) {
                    unlink($lock);
                    return array('result' => 0, 'msg' => $this->dataBase->getErrorMess());
                }
            }
            unlink($lock);
            return array('result' => 1, 'msg' => '还原成功！');
        } else {
            return array('result' => 0, 'msg' => '无备份数据！');
        }
    }

}
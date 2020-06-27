<?php

namespace app\admin\controller;

use think\facade\Config;

class File extends Common
{

    /**
     *
     */
    public function index($dirname = null)
    {
        $dirname   = urldecode($dirname);
        $ROOT_PATH = str_replace(DS."public".DS."..".DS, "", ROOT_PATH);
        if ($dirname) {
            // 是否能够返回上一级
            if (stripos($dirname, $ROOT_PATH) === 0 && stripos($dirname, $ROOT_PATH.'..') === false) {
                if (file_exists(gbk_utf($dirname, false))) {
                    chdir(gbk_utf($dirname, true));// 切换目录
                }
            }
        }
        $rootpath    = getcwd();// 获取工作目录
        $data        = [];
        $num['file'] = 0;
        $num['dir']  = 0;
        $dirFile     = opendir($rootpath);

        while ($fileName = readdir($dirFile)) {
            if ($fileName != '.' && $fileName != '..') {

                // 是否是目录以选择图标并计算目录内的文件大小
                if (is_dir($fileName)) {
                    // 目录
                    $fileInfo['icon'] = '#icon-wenjianjia';
                    $fileInfo['size'] = $this->dirsize($fileName);
                    $fileInfo['dir']  = 1;
                    $num['dir']++;
                } else {
                    // 文件
                    $fileInfo['icon'] = $this->geticon($fileName);
                    $fileInfo['size'] = filesize($fileName);
                    $fileInfo['dir']  = 0;
                    $num['file']++;
                }
                $fileInfo['dirname'] = gbk_utf($rootpath.DIRECTORY_SEPARATOR.$fileName);// 获取绝对路径
                $fileInfo['name']    = gbk_utf($fileName);
                $fileInfo['ctime']   = filectime($fileName);
                $fileInfo['mtime']   = filemtime($fileName);
                $data[]              = $fileInfo;
            }
        }
        array_multisort(array_column($data, 'dir'), SORT_DESC, $data);
        $page = $this->getpage($data, 10, input('page'));

        return $this->fetch('index', [
            'dirs'        => $page['data'],
            'page'        => $page['page'],
            'path'        => gbk_utf($rootpath),
            'path_encode' => base64_encode(gbk_utf($rootpath)),
            'uppath'      => gbk_utf($rootpath).DS.'..',
            'num'         => $num,
        ]);

    }

    // 删除
    public function del()
    {
        if (request()->isPost()) {
            $data = gbk_utf(urldecode(input('data')), false);
            if (is_dir($data)) {
                $count = scandir($data);
                if (count($count) === 2) {
                    if (rmdir($data)) {
                        return $this->result('', 1, '目录删除成功');
                    } else {
                        return $this->result('', 0, '目录删除失败');
                    }
                } else {
                    $res = $this->deldir($data);
                    if ($res) {
                        return $this->result('', 1, '目录删除成功');
                    } else {
                        return $this->result('', 0, '目录删除失败');
                    }
                }
            }
            if (is_file($data)) {
                if (unlink($data)) {
                    return $this->result('', 1, '文件删除成功');
                } else {
                    return $this->result('', 0, '文件删除失败');
                }
            }

            return $data;
        }
    }

    // 重命名
    public function rname()
    {
        if (request()->isPost()) {

            $oldName = urldecode(input('oldname'));

            $name = input('newname');

            $newName = dirname($oldName).'/'.$name;

            if (file_exists($newName) && is_writable($oldName)) {
                return $this->result('', 0, $name.' 文件名已存在');
            }

            try {
                $is = rename(gbk_utf($oldName, false), gbk_utf($newName, false));
            } catch (\Exception $e) {
                return $this->result('', 0, '文件名修改失败');
            }
            if ($is) {
                return $this->result('', 1, '文件名修改成功');
            } else {
                return $this->result('', 0, '文件名修改失败');
            }

            return $this->result('', 0, '操作异常');

        }
    }

    // 文件下载
    public function down()
    {
        // ajax判断一遍文件是否存在，只是为了前台提示，暂时可有可无。
        if (request()->isAjax()) {
            $data = urldecode(input('file'));
            $file = gbk_utf($data, false);
            if ( ! file_exists($file)) {
                return $this->result('', 0, '文件不存在');
            }

            return $this->result('', 1, '');
        }
        $data     = urldecode(input('file'));
        $file     = gbk_utf($data, false);
        $filename = basename($file);
        // 设置头
        header('Content-Disposition:attachment;filename='.$filename);
        readfile($file);// 下载

    }

    // 文件编辑
    public function edit()
    {
        if (input('dosubmit')) {
            $data = base64_decode(input('text'));
            $file = urldecode(input('filename'));
            if (file_put_contents($file, $data)) {
                return $this->result('', 1, '修改成功');
            } else {
                return $this->result('', 0, '修改失败');
            }

        } else {
            $data = urldecode(input('file'));
            $exts = ['PHP', 'HTML', 'JS', 'CSS', 'TXT', 'JSON', 'XML', 'HTACCESS', 'SQL', 'LOG', 'MD'];
            $ext  = strtoupper(pathinfo($data, PATHINFO_EXTENSION));

            if ( ! in_array($ext, $exts)) {
                return $this->result('', 0, '该文件不支持编辑');
            }

            // return $data;
            if (empty($data) || ! file_exists($data)) {
                return $this->result('', 0, '文件不存在');
            }

            $code = htmlentities(file_get_contents($data), ENT_COMPAT, 'UTF-8');

            return $this->fetch('edit', [
                'code'     => $code,
                'ext'      => strtolower($ext),
                'filename' => $data,
                'fontSize' => config('huiadmin.ace_editor_fontSize'),
                'aceTheme' => config('huiadmin.ace_editor_Theme'),
            ]);
        }
    }

    // 分页
    private function getpage($arr, $list = 3, $curr)
    {
        $arrCount = count($arr);
        $total    = ceil($arrCount / $list);// 获取页数
        if ($curr <= 0) {
            $curr = 1;
        }
        if ($curr > $total) {
            $curr = $total;
        }
        $data = array_slice($arr, ($curr - 1) * $list, $list);// 按页数分割数组

        return [
            'data' => $data,
            'page' => [
                'count' => $arrCount,
                'limit' => $list,
                'curr'  => $curr,
            ],
        ];
    }

    // 返回文件图标
    private function geticon($file)
    {
        // 获取文件格式
        $ext = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
        $ico = '';
        switch ($ext) {
            case 'PHP':
                $ico = '#icon-php';
                break;
            case 'HTML':
                $ico = '#icon-html';
                break;
            case 'JS':
                $ico = '#icon-js';
                break;
            case 'CSS':
                $ico = '#icon-css';
                break;
            case 'JSON':
                $ico = '#icon-json';
                break;
            case 'JPG':
                $ico = '#icon-Jpg';
                break;
            case 'PNG':
                $ico = '#icon-png';
                break;
            case 'GIF':
                $ico = '#icon-gif';
                break;
            case 'HTACCESS':
                $ico = '#icon-htaccess';
                break;
            case 'ICO':
                $ico = '#icon-img';
                break;
            case 'BMP':
                $ico = '#icon-bmp';
                break;
            default:
                $ico = '#icon-file';
                break;
        }

        return $ico;
    }

    // 删除含有目录的文件
    public function deldir($path)
    {
        //如果是目录则继续
        if (is_dir($path)) {
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $dirs = opendir($path);
            while ($file = readdir($dirs)) {

                if ($file != '.' && $file != '..') {
                    //如果是目录则递归子目录，继续操作
                    if (is_dir($path.DS.$file)) {
                        $this->deldir($path.DS.$file);
                    } else {
                        unlink($path.DS.$file);
                    }
                }
            }
            closedir($dirs);
            // 最后删除要处理的根目录
            $res = rmdir($path);

            return $res;
        }
    }

    // 获取目录的大小
    public function dirsize($dirname)
    {
        $dir  = opendir($dirname);
        $size = 0;
        while ($fileName = readdir($dir)) {
            if ($fileName != '.' and $fileName != '..') {
                $path = $dirname.DS.$fileName;// 获取文件夹下的目录
                if (is_dir($path)) {
                    $size += $this->dirsize($path);
                } elseif (is_file($path)) {
                    $size += filesize($path);
                }
            }
        }

        return $size;
    }

    //创建文件（文件夹）
    public function create()
    {
        if (input('dosubmit')) {
            $type = input('post.type');
            $name = input('post.name');
            $path = base64_decode(input('path')).DS.$name;
            if ($type == "dir") {
                $create = dir_create($path);
                return json(['status'=>1,'msg'=>'ok']);
            } elseif ($type == "file") {
                $create = create_file($path);
                return json(['status'=>1,'msg'=>'ok']);
            } else {
                return json(['status'=>0,'msg'=>'I do not know what do you want to create!!!']);
            }
        } else {
            $path = input('path');

            return $this->fetch('create', ['path' => $path]);
        }
    }

    public function ace_editor_config()
    {
        $type = input('get.type');
        $text = input('get.text');
        switch ($type) {
            case "fontSize":
                setconfig('huiadmin', ['ace_editor_fontSize'], [$text]);
                break;
            case "setTheme":
                setconfig('huiadmin', ['ace_editor_Theme'], [$text]);
                break;
        }
    }

}
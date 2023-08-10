<?php

namespace app\controller;
use app\BaseController;
use app\common\Status;
use app\model\Image;
use app\common\RegValidate;
use app\pojo\ImageEntity;
use app\pojo\PageEntity;
use think\facade\Filesystem;
use think\exception\ValidateException;

class ImageController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 上传图片
     * 返回图片临时路径
     */
    public function upload() {
        if($this -> request -> isPost()) {
            // 上传对象
            $file = $this -> request -> file();
            // 图片限制尺寸 5m
            $size = 5 * 1024 * 1024;
            try {
                // 验证
                validate(['image' => "fileSize:{$size}|fileExt:jpg,jpeg,png,gif"]) -> check($file);
                // 保存图片到临时路径
                $saveName = Filesystem::disk('tmp') -> putFile('image', $file['image'], function () use ($file) {
                    $img = $file['image'];
                    $dir = date('Ymd');
                    $name = $img -> md5();
                    return "{$dir}/{$name}";
                });
                // 图片对象
                $img = ImageEntity::parseFile($file['image'], $saveName, 'tmp');
                // 返回保存文件
                return result()::success($img);
            } catch (ValidateException $e) {
                return result()::error(431, $e -> getMessage());
            }
        } else {
            return result()::error(Status::IMG_RQM_ERR());
        }
    }

    /**
     * 根据ID获取
     */
    public function getById($id) {
        $data = Image::find($id);
        if($data === null) {
            return result()::error(Status::GET_ERR());
        }
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 根据日期随机获取一条
     * disabled = false
     */
    public function getDaily($day = '', $type = '') {
        // 获取当前日期
        $date = date('Y-m-d');
        // 判断是否有日期传参
        if($day !== '') {
            if(RegValidate::validDate($day)) {
                $date = $day;
            } else {
                return result()::error(Status::create(413, 'day格式不正确，需为yyyy-MM-dd'));
            }
        }
        
        $data = null;
        // 查询当前日期是否有指定数据
        if($type !== '') {
            $data = Image::disabled(0) -> type($type) -> showDate($date) -> find();
        } else {
            $data = Image::disabled(0) -> showDate($date) -> find();
        }
        
        if($data === null) {
            // 根据当前日期计算特定数字
            $list = null;
            $total = 0;
            if($type !== '') {
                $total = Image::disabled(0) -> type($type) -> count();
                if($total > 0) {
                    $timp = strtotime($date) / 100;
                    $current = $timp % $total;
                    $list = Image::disabled(0) -> type($type) -> limit($current, 1) -> select();
                }
            } else {
                $total = Image::disabled(0) -> count();
                if($total > 0) {
                    $timp = strtotime($date) / 100;
                    $current = $timp % $total;
                    $list = Image::disabled(0) -> limit($current, 1) -> select();
                }
            }
            if($list === null || count($list) === 0) return result()::error(Status::GET_ERR());
            $data = $list[0];
        }
        return result()::success($data);
    }

    /**
     * 随机获取一条
     * disabled = false
     */
    public function getRandom($type = '') {
        // 获取数据库数量
        $total = 0;
        $data = null;
        if($type !== '') {
            $total = Image::disabled(0) -> type($type) -> count();
            // 随机指定行
            $current = mt_rand(0, $total - 1);
            // 获取指定行数据
            $data = Image::disabled(0) -> type($type) -> limit($current, 1) -> select();
        } else {
            $total = Image::disabled(0) -> count();
            // 随机指定行
            $current = mt_rand(0, $total - 1);
            // 获取指定行数据
            $data = Image::disabled(0) -> limit($current, 1) -> select();
        }
        if($data === null || count($data) === 0) return result()::error(Status::GET_ERR());
        return result()::success($data[0]);
    }

    /**
     * 分页获取
     */
    public function getPage(int $page = 1, int $pageSize = 5, string $key = '', string $type = 'name') {
        $query = null;
        if($key !== '') {
            switch($type) {
                case 'label':
                    $query = Image::withSearch(['labels'], ['labels' => $key]);
                    break;
                case 'type':
                    $query = Image::withSearch(['type'], ['type' => $key]);
                    break;
                case 'imageType':
                    $query = Image::withSearch(['imageType'], ['imageType' => $key]);
                    break;
                default:
                    $query = Image::withSearch(['name'], ['name' => $key]);
                    break;
            }
        }

        $list = $query -> page($page, $pageSize) -> select();
        $total = $query -> count();
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data);
    }

    /**
     * 新增每日一图
     */
    public function save() {
        $status = Image::add($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 批量添加每日一图
     */
    public function saveBatch() {
        $status = Image::batch($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 修改每日一图
     */
    public function edit() {
        $status = Image::edit($this -> request -> put());
        return result()::success(null, $status);
    }

    /**
     * 删除每日一图
     */
    public function deleteByIds($ids) {
        $idList = preg_split('/,\s*/', $ids);
        $flag = Image::destroy($idList);
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success(null, Status::DEL_OK());
    }
}

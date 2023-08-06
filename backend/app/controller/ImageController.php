<?php

namespace app\controller;
use app\BaseController;
use app\common\Status;
use app\pojo\ImageEntity;
use think\facade\Filesystem;
use think\exception\ValidateException;

class ImageController extends BaseController
{
    public function index()
    {
        return result()::error(Status::IMG_ERR());
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
}

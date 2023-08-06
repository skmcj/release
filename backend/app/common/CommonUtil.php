<?php

namespace app\common;
use app\pojo\ImageEntity;

class CommonUtil {

    /**
     * 保存文件到持久存储路径
     */
    public static function saveImage(ImageEntity $img = null)
    {
        if($img == null) return null;
        // 获取配置路径
        $tmpRoot = app()->getRootPath() . config('common.tmp_dir');
        $root = app()->getRootPath() . config('common.storage_dir');
        $host = config('common.img_host');
        $dir = dechex(date('Ymd'));

        $tmpImgName = $img -> getName();

        // 保存图片的文件夹
        $path = "{$root}image/{$dir}/";
        // 临时图片路径
        $tmpPath = "{$tmpRoot}{$tmpImgName}";
        // 如果没有该文件夹，则创建
        if(!is_dir($path))
            mkdir($path, 777, true);
        $imgName = basename($tmpImgName);
        $imgPath = "{$path}{$imgName}";
        // 将文件移动到持久存储路径
        if(file_exists($tmpPath)) {
            // 剪切到持久路径
            $flag = rename($tmpPath, $imgPath);
            if($flag) return new ImageEntity($img -> getSize(), "image/{$dir}/{$imgName}", "{$host}image/{$dir}/{$imgName}", $img -> getType());
        }
        return null;
    }

    /**
     * 根据图片临时名称保存
     */
    public static function saveImageByTmp($tmpImgName = '') {

        if($tmpImgName === '') return null;

        $tmpRoot = app()->getRootPath() . config('common.tmp_dir');
        $root = app()->getRootPath() . config('common.storage_dir');
        $host = config('common.img_host');
        $dir = dechex(date('Ymd'));



        // 保存图片的文件夹
        $path = "{$root}image/{$dir}/";
        // 临时图片路径
        $tmpPath = "{$tmpRoot}{$tmpImgName}";
        // 如果没有该文件夹，则创建
        if(!is_dir($path))
            mkdir($path, 777, true);

        $imgName = basename($tmpImgName);
        $imgPath = "{$path}{$imgName}";
        // 将文件移动到持久存储路径
        if(file_exists($tmpPath)) {
            // 剪切到持久路径
            $flag = rename($tmpPath, $imgPath);
            if($flag) return new ImageEntity(filesize($imgPath), "image/{$dir}/{$imgName}", "{$host}image/{$dir}/{$imgName}", pathinfo($imgPath, PATHINFO_EXTENSION));
        }
        return null;
    }
}
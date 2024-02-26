<?php

namespace app\common;

/**
 * 正则验证工具
 */
class RegValidate {

    /**
     * 验证是否为 https | http 链接
     */
    public static function validUrl($url) {
        $patt = '/^(((ht|f)tps?):\/\/)([^!@#$%^&*?.\s-]([^!@#$%^&*?.\s]{0,63}[^!@#$%^&*?.\s])?\.)+[a-z]{2,6}\/?/';
        return preg_match($patt, $url) > 0;
    }
    
    /**
     * 验证是否为 中文URL编码
     */
    public static function validUrlCode($url) {
        $patt = '/(%[0-9a-fA-f]{2})+/';
        return preg_match($patt, $url) > 0;
    }

    /**
     * 验证日期 yyyy-MM-dd
     */
    public static function validDate($date) {
        $patt = '/^\d{1,4}(-)(1[0-2]|0?[1-9])\1(0?[1-9]|[1-2]\d|30|31)$/';
        return preg_match($patt, $date) > 0;
    }

    /**
     * 验证是否临时路径图片
     */
    public static function validTemp($url) {
        $patt = config('common.img_tmp_reg');
        return preg_match($patt, $url) > 0;
    }

    
}
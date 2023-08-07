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
}
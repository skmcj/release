<?php

namespace app\common;

use Exception;
use XdbSearcher;

class IPRegionUtil {
    private static $dbPath = '';

    private static $searcher = null;

    private static function init() {
        self::$dbPath = config('common.ipdb');
        try {
            self::$searcher = XdbSearcher::newWithFileOnly(self::$dbPath);
        } catch (Exception $e) {
            // printf("failed to create searcher with '%s': %s\n", $dbPath, $e);
            return null;
        }
    }

    public static function parseIp($ip) {
        if(self::$searcher === null) self::init();
        $region = self::$searcher->search($ip);
        if ($region === null) {
            return null;
        }
        $loaction = explode('|', $region);
        return [
            "country" => $loaction[0],
            "region" => $loaction[1],
            "province" => $loaction[2],
            "city" => $loaction[3],
            "isp" => $loaction[4],
            "lct" => self::encodeLocation($loaction)
        ];
    }

    public static function now() {
        return XdbSearcher::now();
    }

    /**
     * 将地址信息压缩
     */
    public static function encodeLocation(array $location) {
        if($location === null) return null;
        $lct = '';
        if($location[0] === '中国') {
            if($location[3] === '0' || $location[2] === '0') {
                $lct = "{$location[0]}·{$location[2]}·{$location[3]}";
            } else {
                $lct = "{$location[2]}·{$location[3]}";
            }
            $lct = preg_replace('/省|市|·0|0|北京·|天津·|上海·|重庆·/', '', $lct);
        } else {
            if($location[0] === '0') {
                $lct = "{$location[2]}·{$location[3]}";
            } else {
                $lct = "{$location[0]}·{$location[2]}";
            }
            $lct = preg_replace('/0·|·0|0/', '', $lct);
        }
        return $lct;
    }
}
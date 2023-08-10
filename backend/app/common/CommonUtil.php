<?php

namespace app\common;
use app\pojo\ImageEntity;
use Exception;
use XdbSearcher;

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

    /**
     * 计算日期差值
     */
    public static function caleDateDiff(string $start, string $end) {
        $startDate = date_create($start);
        $endDate = date_create($end);
        return date_diff($startDate, $endDate);
    }

    /**
     * 计算当前日期差值
     */
    public static function caleDateDiffOfCurrent(string $start) {
        $startDate = date_create($start);
        $endDate = date_create(date('Y-m-d H:i:s'));
        return date_diff($startDate, $endDate);
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function getClientIp($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * 获取ip归属地
     * @param string $ip IP地址
     * - 其它可用api
     *   - https://api.vore.top/api/IPdata?ip={$ip}
     *   - http://opendata.baidu.com/api.php?query={$ip}&co=&resource_id=6006&oe=utf8
     * 
     */
    public static function getIpLocationV1($ip){     	
        $ch = curl_init();
        $url = 'https://www.fkcoder.com/ip?ip='.$ip;
        //用curl发送接收数据
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //请求为https
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $location = curl_exec($ch);
        curl_close($ch);
        //转码
        // $location = mb_convert_encoding($location, 'utf-8','GB2312');
        $location = json_decode($location);
        //截取{}中的字符串
        
        return $location;
    }

    /**
     * 获取ip归属地
     * @param string $ip IP地址
     * 使用ipRegion
     */
    public static function getIpLocationV2($ip){
        $dbPath = config('common.resource').'data/ip2region.xdb';
        try {
            $searcher = XdbSearcher::newWithFileOnly($dbPath);
        } catch (Exception $e) {
            // printf("failed to create searcher with '%s': %s\n", $dbPath, $e);
            return null;
        }
        $sTime = XdbSearcher::now();
        $region = $searcher->search($ip);
        if ($region === null) {
            // something is wrong
            // printf("failed search(%s)\n", $ip);
            return null;
        }
        // printf("{region: %s, took: %.5f ms}\n", $region, XdbSearcher::now() - $sTime);
        $loaction = explode('|', $region);
        return [
            "country" => $loaction[0],
            "region" => $loaction[1],
            "province" => $loaction[2],
            "city" => $loaction[3],
            "isp" => $loaction[4]
        ];
    }


}
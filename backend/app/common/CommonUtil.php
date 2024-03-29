<?php

namespace app\common;

use app\model\Image;
use app\pojo\ImageEntity;
use ErrorException;
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
            mkdir($path, 0777, true);
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
            mkdir($path, 0777, true);

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
     * 根据图片临时名称保存
     */
    public static function saveImageByTmpUrl($tmpImgUrl = '') {

        if($tmpImgUrl === '') return null;
        $patt = config('common.img_tmpn_reg');
        $maches = '';
        $mh = preg_match($patt, $tmpImgUrl, $maches);
        if($mh <= 0) return null;
        $tmpImgName = $maches[1];
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
            mkdir($path, 0777, true);

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
     * @return array|null
     */
    public static function getIpLocationV2($ip){
        return IPRegionUtil::parseIp($ip);
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

    public static function countArticle($content = '') {
        $mdReg = "/#{1,6} |- |`|> |\n|\r| |\*\*|<|>|\/|\"|\'|\[|\]/";
        $str = preg_replace($mdReg, '', $content);
        return mb_strlen($str);
    }


    /**
     * 编辑文章
     */
    public static function editArticle($name = '', $content = '') {
        if($name === '') throw new ErrorException('文件名为空');
        $file = fopen(config('common.article_dir').$name.'.md', 'w');
        fwrite($file, $content);
        $count = self::countArticle($content);
        fclose($file);
        return [
            'name' => $name,
            'count' => $count
        ];
    }

    /**
     * 删除文章
     */
    public static function deleteArticle($name = '') {
        if($name === '') throw new ErrorException('文件名为空');
        $filepath = config('common.article_dir').$name.'.md';
        if(file_exists($filepath)) {
            $res = unlink($filepath);
            return $res;
        } else {
            return false;
        }
    }

    public static function readArticle($name = '') {
        if($name === '') throw new ErrorException('文件名为空');
        $filepath = config('common.article_dir').$name.'.md';
        if(file_exists($filepath)) {
            $res = file_get_contents($filepath);
            return $res;
        } else {
            return false;
        }
    }

    /**
     * 重命名文章
     */
    public static function renameArticle($oldName = '', $newName = '') {
        if($oldName === '') throw new ErrorException('文件名为空');
        $newPath = config('common.article_dir').$newName.'.md';
        $oldPath= config('common.article_dir').$oldName.'.md';
        rename($oldPath, $newPath);
        return $newName;
    }

    public static function getImageDaily() {
        // 获取当前日期
        $date = date('Y-m-d');
        $total = Image::disabled(0) -> count();
        if($total > 0) {
            $timp = strtotime($date) / 100;
            $current = $timp % $total;
            $list = Image::disabled(0) -> type('pc') -> limit($current, 1) -> select();
        }
        if($list === null || count($list) === 0) return config('common.img_host').'/134b296/7025e42fa97b4a21ba2becc7bbdde3e9.png';
        return $list[0] -> url;
    }


}
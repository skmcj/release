<?php

namespace app\common;

/**
 * 状态枚举
 */
class Status {
    /**
     * 状态码
     */
    private int $code;

    /**
     * 信息
     */
    private string $msg;

    public function __construct(int $code = 599, string $msg = 'Unknown Error') {
        $this -> code = $code;
        $this -> msg = $msg;
    }

    public function getCode() {
        return $this -> code;
    }

    public function getMsg() {
        return $this -> msg;
    }

    /**
     * 请求成功
     */
    public static function OK() {
        return new Status(200, '请求成功');
    }

    /**
     * 请求失败
     */
    public static function Error() {
        return new Status(500, '请求失败');
    }

    /**
     * 404
     */
    public static function NOTFOUND() {
        return new Status(404, '请求资源不存在');
    }

    /**
     * 未知错误
     */
    public static function UNKNOWN() {
        return new Status(599, '未知错误');
    }

    /**
     * 服务器出错
     */
    public static function SERVICE_ERR() {
        return new Status(512, '系统繁忙，请稍后再试！');
    }

    /** 图片请求相关 */
    public static function IMG_RQM_ERR() {
        return new Status(403, '请求失败，请检查请求方法是否正确');
    }
    /**
     * 图片上传
     * 431 图片验证失败
     */

    /** 用户相关 */
    public static function USER_FIND_ERR() {
        return new Status(414, 'ID错误，查无该用户');
    }

    public static function USER_DEL_ERR() {
        return new Status(412, '删除失败，请稍候再试');
    }
    public static function USER_DEL_OK() {
        return new Status(212, '删除成功');
    }


    public static function COMMON_ERR() {
        return new Status(403, '请求失败，请检查请求参数是否正确');
    }


}
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
     * 404
     */
    public static function UNKNOWN() {
        return new Status(599, '未知错误');
    }
}
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

    public static function ADD_OK($msg = '添加成功') {
        return new Status(211, $msg);
    }
    public static function DEL_OK($msg = '删除成功') {
        return new Status(212, $msg);
    }
    public static function EDIT_OK($msg = '修改成功') {
        return new Status(213, $msg);
    }
    public static function GET_OK($msg = '查询成功') {
        return new Status(214, $msg);
    }

    
    public static function ADD_ERR($msg = '添加失败') {
        return new Status(411, $msg);
    }
    public static function DEL_ERR($msg = '删除失败') {
        return new Status(412, $msg);
    }
    public static function EDIT_ERR($msg = '修改失败') {
        return new Status(413, $msg);
    }
    public static function GET_ERR($msg = '查询失败') {
        return new Status(414, $msg);
    }


    /**
     * 图片上传
     * 431 图片验证失败
     */

    /** 用户相关 */
    public static function USER_FIND_ERR() {
        return new Status(414, 'ID错误，查无该用户');
    }

    public static function USER_GET_ERR() {
        return new Status(414, '查询失败，没有更多数据了');
    }

    public static function USER_DEL_ERR() {
        return new Status(412, '删除失败，请稍候再试');
    }
    public static function USER_DEL_OK() {
        return new Status(212, '删除成功');
    }

    public static function COMMON_FIND_ERR() {
        return new Status(414, 'ID错误，查无该记录');
    }


    public static function COMMON_ERR() {
        return new Status(403, '请求失败，请检查请求参数是否正确');
    }

    public static function IP_PARSE_ERR() {
        return new Status(512, 'ip获取失败，请稍后再试');
    }

    // 登录

    public static function LOGIN_OK() {
        return new Status(220, '登录成功');
    }

    public static function LOGIN_ERR() {
        return new Status(420, '登录失败');
    }

    public static function LOGOUT_OK() {
        return new Status(221, '退出成功');
    }

    public static function LOGOUT_ERR() {
        return new Status(421, '退出失败');
    }

    public static function TOKEN_OK($msg = 'Token 校验成功') {
        return new Status(222, $msg);
    }

    public static function TOKEN_ERR($msg = 'Token 校验失败') {
        return new Status(422, $msg);
    }

    public static function TOKEN_FAIL($msg = 'Token 异常') {
        return new Status(423, $msg);
    }

    public static function TOKEN_UNKNOWN($msg = 'Token 未知错误') {
        return new Status(423, $msg);
    }

    public static function create(int $code = 599, string $msg = '未知错误') {
        return new Status($code, $msg);
    }

    


}
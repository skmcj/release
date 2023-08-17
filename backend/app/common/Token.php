<?php

namespace app\common;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token {

    private static $key = null;
    private static $exp = 0;
    

    private static function initToken() {
        self::$key = config('token.key');
        self::$exp = config('token.exp');
    }

    /**
     * 创建 token
     * @param array $data 自定义参数数组
     * @param integer $exp token 过期时间 单位:秒 例子：900 = 15分钟
     * @return string
     */
    public static function createToken($data = [], $exp = 0, $alg = 'HS256') {
        if(self::$key === null) self::initToken();
        $now = time();
        $payload = [
            "iss" => 'skmcj',        //签发者 可以为空
            "aud" => 'role',         //面象的用户，可以为空
            "iat" => $now,           // 签发时间
            "nbf" => $now,           // 生效时间，立马生效
            "exp" => $now + ($exp === 0 ? self::$exp : $exp),    //token 过期时间 两小时
        ];
        if(!empty($data)) {
            $payload['data'] = $data;
        }
        $token = JWT::encode($payload, self::$key, $alg);
        return $token;
    }

    /**
     * 创建 token
     * @param array $data 自定义参数数组
     * @param integer $exp token 过期时间 单位:秒 例子：900 = 15分钟
     * @return string
     */
    public static function createTokenById($id = '', $exp = 0, $alg = 'HS256') {
        if(self::$key === null) self::initToken();
        $now = time();
        $payload = [
            "iss" => 'skmcj',        //签发者 可以为空
            "aud" => 'role',         //面象的用户，可以为空
            "iat" => $now,           // 签发时间
            "nbf" => $now,           // 生效时间，立马生效
            "exp" => $now + ($exp === 0 ? self::$exp : $exp),    //token 过期时间 两小时
        ];
        if(!empty($id)) {
            $payload['data'] = [
                'id' => $id
            ];
        }
        $token = JWT::encode($payload, self::$key, $alg);
        return $token;
    }

    /**
     * 校验token
     */
    public static function checkToken($token, $alg = 'HS256') {
        if(self::$key === null) self::initToken();
        $res = [
            'flag' => false,
            'data' => null,
            'msg' => ''
        ];
        try {
            JWT::$leeway = 60;  //当前时间减去60，把时间留点余地
            $decoded = JWT::decode($token, new Key(self::$key, $alg)); //HS256方式，这里要和签发的时候对应
            $payload = (array)$decoded;
            $res['flag'] = true;
            $res['data'] = $payload['data'];
            $res['exp'] = $payload['exp'];
            return $res;

        } catch(\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            $res['flag'] = false;
            $res['msg']="Token 签名不正确";
            return $res;
        }catch(\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
            $res['flag'] = false;
            $res['msg']="Token无效";
            return $res;
        }catch(\Firebase\JWT\ExpiredException $e) { // token过期
            $res['flag'] = false;
            $res['msg']="Token已过期，请重新登录";
            return $res;
        }catch(\Exception $e) { //其他错误
            $res['flag'] = false;
            $res['msg']="未知错误";
            return $res;
        }

    }
}
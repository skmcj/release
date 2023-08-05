<?php

namespace app\common;


class Result
{
    public static function success(...$args)
    {
        if(count($args) === 1) {
            return Result::success1($args[0]);
        }
        if(count($args) === 2) {
            return Result::success2($args[0], $args[1]);
        }
        if(count($args) === 3) {
            return Result::success3($args[0], $args[1], $args[2]);
        }
    }

    public static function error(...$args)
    {
        if(count($args) === 1) {
            return Result::error1($args[0]);
        }
        if(count($args) === 2) {
            return Result::error2($args[0], $args[1]);
        }
        if(count($args) === 3) {
            return Result::error3($args[0], $args[1], $args[2]);
        }
    }

    private static function success1($data = null)
    {
        return json([
            'code' => Status::OK() -> getCode(),
            'msg' => Status::OK() -> getMsg(),
            'data' => $data
        ]);
    }

    
    private static function success2($data = null, Status $status = null)
    {
        if($status == null) {
            $status = Status::OK();
        }

        return json([
            'code' => $status -> getCode(),
            'msg' => $status -> getMsg(),
            'data' => $data
        ]);
    }

    private static function success3($data = null, $code = 200, $msg = '请求成功')
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    private static function error1(Status $status = null)
    {
        if($status == null) {
            $status = Status::Error();
        }

        return json([
            'code' => $status -> getCode(),
            'msg' => $status -> getMsg(),
            'data' => null
        ]);
    }

    private static function error2($code = 599, $msg = '未知错误')
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => null
        ]);
    }

    private static function error3($code = 599, $msg = '未知错误', $data = null)
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}

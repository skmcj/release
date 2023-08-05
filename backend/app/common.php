<?php
// 应用公共文件

use app\common\Result;

if (!function_exists('result')) {
    /**
     * 获取当前json对象实例.
     *
     * @return object|Result
     */
    function result()
    {
        return app('result');
    }
}

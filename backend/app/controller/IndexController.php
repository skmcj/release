<?php

namespace app\controller;

use app\BaseController;
use app\common\Status;

class IndexController extends BaseController
{
    public function index()
    {
        return result()::success(null, 200, '欢迎使用 release-skmcj Api');
    }

    public function test() {
        return result()::success('test');
    }

    public function hello($name = 'ThinkPHP8')
    {
        return result()::success('hello,' . $name, Status::Error());
    }
}

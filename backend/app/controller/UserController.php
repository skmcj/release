<?php

namespace app\controller;
use app\BaseController;
use app\common\Status;
use app\model\User;
use think\Request;

class UserController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 根据ID获取用户
     */
    public function getById($id = '') {
        $user = User::find($id);
        return result()::success($user);
    }

    /**
     * 根据ID删除用户
     */
    public function deleteById($id = '') {
        return result()::success('delete '.$id);
    }

    /**
     * 保存用户
     */
    public function save() {
        $status = User::add($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 修改用户
     */
    public function update() {
        $status = User::edit($this -> request -> put());
        return result()::success(null, $status);
    }
}

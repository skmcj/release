<?php

namespace app\controller;
use app\BaseController;
use app\common\CommonUtil;
use app\common\Status;
use app\model\Social;
use app\model\User;
use app\pojo\PageEntity;

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
        if($user === null) {
            return result()::error(Status::USER_FIND_ERR());
        }
        return result()::success($user);
    }

    /**
     * 根据ID获取用户
     */
    public function getAllById($id = '') {
        $user = User::find($id);
        if($user === null) {
            return result()::error(Status::USER_FIND_ERR());
        }
        $days = CommonUtil::caleDateDiffOfCurrent($user -> startTime) -> days;
        // 获取用户设计信息
        $list = Social::userId($id) -> disabled(0) -> order('sort', 'desc') -> select();
        $user -> social = $list;
        $user -> days = $days;
        return result()::success($user);
    }

    /**
     * 获取缩略列表
     */
    public function getList() {
        $user = User::scope('sm') -> select();
        if($user === null) {
            return result()::error(Status::USER_GET_ERR());
        }
        return result()::success($user);
    }

    /**
     * 分页获取
     */
    public function getPage(int $page = 1, int $pageSize = 5) {
        $user = User::page($page, $pageSize) -> select();
        if($user === null) {
            return result()::error(Status::USER_GET_ERR());
        }
        $total = User::count();
        $page = new PageEntity($user, $total, $page, $pageSize);
        return result()::success($page);
    }

    /**
     * 根据ID删除用户
     */
    public function deleteById($id = '') {
        $user = User::find($id);
        if($user === null) {
            return result()::error(Status::USER_FIND_ERR());
        }
        $flag = $user -> delete();
        if(!$flag) return result()::error(Status::USER_DEL_ERR());
        return result()::success($flag, Status::USER_DEL_OK());
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

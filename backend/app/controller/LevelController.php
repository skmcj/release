<?php

namespace app\controller;
use app\BaseController;
use app\common\Status;
use app\model\Level;
use app\pojo\PageEntity;

class LevelController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 根据ID获取数据
     */
    public function getById($id) {
        $data = Level::find($id);
        if($data === null) {
            return result()::error(Status::GET_ERR());
        }
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 根据ID获取数据
     */
    public function getList() {
        $data = Level::scope('sm') -> order('sort', 'desc') -> select();
        if($data === null) {
            return result()::error(Status::GET_ERR());
        }
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 分页获取
     */
    public function getPage(int $page = 1, int $pageSize = 5) {
        $data = Level::page($page, $pageSize) -> order('sort', 'desc') -> select();
        if($data === null) {
            return result()::error(Status::USER_GET_ERR());
        }
        $total = Level::count();
        $page = new PageEntity($data, $total, $page, $pageSize);
        return result()::success($page, Status::GET_OK());
    }


    /**
     * 新增数据
     */
    public function save() {
        $status = Level::add($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 修改数据
     */
    public function edit() {
        $status = Level::edit($this -> request -> put());
        return result()::success(null, $status);
    }

    /**
     * 根据ID删除
     */
    public function deleteById($id) {
        $data = Level::find($id);
        if($data === null) {
            return result()::error(Status::COMMON_FIND_ERR());
        }
        $flag = $data -> delete();
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success($flag, Status::DEL_OK());
    }
}

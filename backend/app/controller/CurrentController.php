<?php

namespace app\controller;

use app\BaseController;
use app\common\Status;
use app\model\Current;
use app\pojo\PageEntity;

class CurrentController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    public function getCurrent() {
        $current = Current::order('sort', 'desc') -> find();
        if($current === null) return result()::error(414, '没有指定数据');
        return result()::success($current, Status::GET_OK());
    }

    public function getById($id) {
        $current = Current::find($id);
        if($current === null) return result()::error(Status::GET_ERR());
        return result()::success($current, Status::GET_OK());
    }

    public function getPage($page = 1, $pageSize = 5) {
        $query = new Current();
        $list = $query -> page($page, $pageSize) -> order('sort', 'desc') -> select();
        $total = $query -> count();
        
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data, Status::GET_OK());
    }

    public function save() {
        $status = Current::add($this -> request -> post());
        return result()::success(null, $status);
    }

    public function edit() {
        $status = Current::edit($this -> request -> put());
        return result()::success(null, $status);
    }

    public function deleteById($id) {
        // 如果表内仅剩一个数据，不可删除
        $current = Current::id($id) -> find();
        if($current === null) {
            return result()::error(Status::COMMON_FIND_ERR());
        }
        $total = Current::count();
        if($total <= 1) return result()::error(Status::create(412, '最后一条数据，不可删除'));
        $flag = Current::destroy($id);
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success(Status::DEL_OK());
    }
}

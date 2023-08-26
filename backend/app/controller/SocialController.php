<?php

namespace app\controller;

use app\BaseController;
use app\common\Status;
use app\model\Social;
use app\pojo\PageEntity;

class SocialController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }


    /**
     * 根据ID获取社交信息
     */
    public function getById($id = '') {
        $social = Social::find($id);
        if($social === null) {
            return result()::error(Status::COMMON_FIND_ERR());
        }
        return result()::success($social, Status::GET_OK());
    }

    /**
     * 获取用户所有社交信息-后台管理
     */
    public function getByUserId($id = '') {
        $list = Social::userId($id) -> order('sort', 'desc') -> select();
        return result()::success($list, Status::GET_OK());
    }

    /**
     * 获取用户所有社交信息-前台展示
     * 只获取可用信息
     */
    public function getLinkByUserId($id = '') {
        $list = Social::userId($id) -> disabled(0) -> order('sort', 'desc') -> select();
        return result()::success($list, Status::GET_OK());
    }

    /**
     * 分页获取所有社交信息，后台管理
     */
    public function getAll($page = 1, $pageSize = 5, $key = '') {
        if($page < 0 || $pageSize < 0) return result()::error(Status::COMMON_ERR());
        $query = new Social();
        if($key !== '') {
            $query = Social::userId($key);
        }
        $list =  $query -> page($page, $pageSize) -> order('sort', 'desc') -> select();
        $total = $query -> count();
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 新增用户社交信息
     */
    public function save() {
        $status = Social::add($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 修改用户社交信息
     */
    public function edit() {
        $status = Social::edit($this -> request -> put());
        return result()::success(null, $status);
    }

    /**
     * 删除用户社交信息
     */
    public function deleteById($id) {
        $data = Social::find($id);
        if($data === null) {
            return result()::error(Status::COMMON_FIND_ERR());
        }
        $flag = $data -> delete();
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success($flag, Status::DEL_OK());
    }

}

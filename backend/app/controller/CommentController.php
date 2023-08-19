<?php

namespace app\controller;
use app\BaseController;
use app\common\CommonUtil;
use app\common\Status;
use app\model\Comment;
use app\pojo\PageEntity;

class CommentController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 根据ID获取
     */
    public function getById($id) {
        $data = Comment::find($id);
        if($data === null) {
            return result()::error(Status::GET_ERR());
        }
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 后台分页搜索留言
     */
    public function getPage($page = 1, $pageSize = 5, $key = '', $type = 'content', $visible = null, $disibled = null) {
        $query = null;
        if($key !== '') {
            switch($type) {
                case 'nickname':
                    $query = Comment::withSearch(['nickname'], ['nickname' => $key]);
                    break;
                case 'email':
                    $query = Comment::withSearch(['email'], ['email' => $key]);
                    break;
                case 'address':
                    $query = Comment::withSearch(['address'], ['address' => $key]);
                    break;
                default:
                    $query = Comment::withSearch(['content'], ['content' => $key]);
                    break;
            }
        }
        if($query === null) {
            $query = new Comment();
        }
        if($visible !== null) {
            if($visible === 'true') {
                $query -> visibled(1);
            } elseif($visible === 'false') {
                $query -> visibled(0);
            }
        }
        if($disibled !== null) {
            if($disibled === 'true') {
                $query -> disabled(1);
            } elseif($disibled === 'false') {
                $query -> disabled(0);
            }
        }

        $list = $query -> order('update_time', 'desc') -> page($page, $pageSize) -> select();
        $total = $query -> count();
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data);
    }

    /**
     * 前台分页获取留言
     */
    public function getComment($page = 1, $pageSize = 5) {
        $query = Comment::disabled(0) -> visibled(0);
        $list = $query -> field('id,nickname,content,address, update_time') -> order('update_time', 'desc') -> page($page, $pageSize) -> select();
        if($list === null) {
            return result()::error(Status::GET_ERR());
        }
        $total = Comment::disabled(0) -> visibled(0) -> count();
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data);
    }

    /**
     * 添加
     * @param string $ip 用于测试使用
     */
    public function save($ip = '') {
        if($ip === '') {
            $ip = CommonUtil::getClientIp(0, true);
        }

        $data = $this -> request -> post();
        $address = CommonUtil::getIpLocationV2($ip);
        $data['ip'] = $ip;
        $data['address'] = $address !== null ? $address['lct'] : '';
        $status = Comment::add($data);
        return result()::success(null, $status);
    }

    /**
     * 编辑
     */
    public function edit($ip = '') {
        // TODO: 测试
        if($ip === '') {
            $ip = CommonUtil::getClientIp(0, true);
        }

        $data = $this -> request -> put();

        $address = CommonUtil::getIpLocationV2($ip);
        $data['ip'] = $ip;
        $data['address'] = $address !== null ? $address['lct'] : '';

        $status = Comment::edit($data);
        return result()::success(null, $status);
    }

    /**
     * 根据ID删除
     */
    public function deleteById($id) {
        $data = Comment::find($id);
        if($data === null) {
            return result()::error(Status::COMMON_FIND_ERR());
        }
        $flag = $data -> delete();
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success($flag, Status::DEL_OK());
    }

}

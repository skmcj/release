<?php

namespace app\controller;
use app\BaseController;
use app\common\Status;
use app\model\Article;
use app\model\Product;
use app\model\ProductLabel;
use app\pojo\PageEntity;

class ProductController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 根据ID获取信息
     */
    public function getById($id) {
        // 获取作品信息
        $data = Product::find($id);
        if($data === null) {
            return result()::error(Status::GET_ERR());
        }
        // 将logo转化为url
        // 获取文章信息
        if(!empty($data -> articleId)) {
            $article = Article::disabled(0) -> find($data -> articleId);
            if($article !== null)
                $data -> article = $article;
        }
        $labels = ProductLabel::findPid($id);
        $data -> labels = $labels;
        // 获取标签内容
        return result()::success($data, Status::GET_OK());
    }

    public function getIDList() {
        $list = Product::scope('sm') -> order('comp_date', 'desc') -> select();
        return result()::success($list, Status::GET_OK());
    }

    /**
     * 后台管理作品
     */
    public function getPage($page = 1, $pageSize = 5, $key = '') {
        $query = new Product();
        if($key !== '') {
            $query = Product::searchName($key);
        }
        $list = $query -> page($page, $pageSize) -> select();
        $total = $query -> count();
        if($list === null || count($list) <= 0) return result()::error(Status::GET_ERR());
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 前台展示作品
     */
    public function getPageFront($page = 1, $pageSize = 5) {
        $query = Product::disabled(0);
        $list = $query -> page($page, $pageSize) -> order('comp_date', 'desc') -> select();
        $total = $query -> count();
        if($list === null || count($list) <= 0) return result()::error(Status::GET_ERR());
        // 遍历作品列表
        foreach($list as $item) {
            if(!empty($item -> articleId)) {
                $article = Article::disabled(0) -> find($item -> articleId);
                if($article !== null)
                    $item -> article = $article;
            }
            $labels = ProductLabel::findPid($item -> id);
            $item -> labels = $labels;
        }
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 新增作品
     */
    public function save() {
        $status = Product::add($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 修改作品
     */
    public function edit() {
        $status = Product::edit($this -> request -> put());
        return result()::success(null, $status);
    }

    /**
     * 删除作品
     */
    public function deleteById($id) {
        $flag = Product::destroy($id);
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success(null, Status::DEL_OK());
    }
}

<?php

namespace app\controller;
use app\BaseController;
use app\common\Status;
use app\model\ProductLabel;
use app\pojo\PageEntity;

class ProductLabelController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 新增标签
     */
    public function save() {
        $status = ProductLabel::add($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 修改标签
     */
    public function edit() {
        $status = ProductLabel::edit($this -> request -> put());
        return result()::success(null, $status);
    }

    /**
     * 删除标签
     */
    public function deleteById($id) {
        $data = ProductLabel::find($id);
        if($data === null) return result()::error(Status::COMMON_FIND_ERR());
        $flag = ProductLabel::destroy($id);
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success(null, Status::DEL_OK());
    }

    /**
     * 新增标签
     */
    public function getPage($page = 1, $pageSize = 5, $key = '', $type = 'label') {
        $query = new ProductLabel();
        if($key !== '') {
            switch($type) {
                case 'pid':
                    $query = ProductLabel::pid($key);
                    break;
                case 'label':
                    $query = ProductLabel::label($key);
                    break;

            }
        }
        $list = $query -> order('sort', 'desc') -> page($page, $pageSize) -> select();
        $total = $query -> count();
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data);
    }
}

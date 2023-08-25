<?php

namespace app\controller;
use app\BaseController;
use app\common\RegValidate;
use app\common\Status;
use app\model\Sentence;
use app\pojo\PageEntity;

class SentenceController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 根据ID获取
     */
    public function getById($id) {
        $data = Sentence::find($id);
        if($data === null) {
            return result()::error(Status::GET_ERR());
        }
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 根据日期随机获取一条
     * disabled = false
     */
    public function getDaily() {
        // 获取当前日期
        $date = date('Y-m-d');
        // 判断是否有日期传参
        $day = $this -> request -> param('day');
        if($day !== null) {
            if(RegValidate::validDate($day)) {
                $date = $day;
            } else {
                return result()::error(Status::create(413, 'day格式不正确，需为yyyy-MM-dd'));
            }
        }
        
        $data = null;
        // 查询当前日期是否有指定数据
        $data = Sentence::disabled(0) -> showDate($date) -> find();
        if($data === null) {
            // 根据当前日期计算特定数字
            $total = Sentence::disabled(0) -> count();
            $timp = round(strtotime($date) / 86400);
            $current = $timp % $total;
            $list = Sentence::disabled(0) -> limit($current, 1) -> select();
            if($list === null || count($list) === 0) return result()::error(Status::GET_ERR());
            $data = $list[0];
        }
        return result()::success($data);
    }

    /**
     * 随机获取一条
     * disabled = false
     */
    public function getRandom() {
        // 获取数据库数量
        $total = Sentence::disabled(0) -> count();
        // 随机指定行
        $current = mt_rand(0, $total - 1);
        // 获取指定行数据
        $data = Sentence::disabled(0) -> limit($current, 1) -> select();
        if($data === null || count($data) === 0) return result()::error(Status::GET_ERR());
        return result()::success($data[0]);
    }

    /**
     * 分页获取
     */
    public function getPage(int $page = 1, int $pageSize = 5, string $key = '') {
        $list = Sentence::withSearch(['content'], ['content' => $key]) -> order('update_time', 'desc') -> page($page, $pageSize) -> select();
        $total = Sentence::withSearch(['content'], ['content' => $key]) -> count();
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data);
    }

    /**
     * 新增每日一言
     */
    public function save() {
        $status = Sentence::add($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 批量添加每日一言
     */
    public function saveBatch() {
        $status = Sentence::batch($this -> request -> post());
        return result()::success(null, $status);
    }

    /**
     * 修改每日一言
     */
    public function edit() {
        $status = Sentence::edit($this -> request -> put());
        return result()::success(null, $status);
    }

    /**
     * 删除每日一言
     */
    public function deleteByIds($ids) {
        $idList = preg_split('/,\s*/', $ids);
        $flag = Sentence::destroy($idList);
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success(null, Status::DEL_OK());
    }
}

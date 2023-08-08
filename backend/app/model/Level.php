<?php

namespace app\model;
use app\common\IDGenerator;
use app\common\Status;
use app\validate\LevelValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Level extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;
    protected $schema = [
        'id' => 'string',
        'tip' => 'string',
        'labels' => 'string',
        'sort' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    protected $type = [
        'labels' => 'array'
    ];

    public static function onBeforeInsert($data) {
        if($data -> id === null || $data -> id === '') {
            $data -> id = IDGenerator::getId();
        }
    }

    /**
     * 获取缩略信息
     */
    public function scopeSm($query) {
        $query -> field('id, tip');
    }

    /**
     * 新增数据
     */
    public static function add($data) {
        try {
            validate(LevelValidate::class)
                -> scene('add')
                ->check($data);
            self::create($data);
            return Status::ADD_OK();
        } catch (ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    /**
     * 修改数据
     */
    public static function edit($data) {
        try {
            validate(LevelValidate::class)
                ->check($data);
            // 过滤字段
            self::update($data, ['id' => $data['id']], ['tip', 'labels', 'sort']);
            return Status::EDIT_OK();
        } catch (ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }
    
}

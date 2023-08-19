<?php

namespace app\model;
use app\common\IDGenerator;
use app\common\Status;
use app\validate\CurrentValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Current extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    // 设置字段信息
    protected $schema = [
        'id'            => 'string',
        'user_id'       => 'string',
        'level_id'      => 'string',
        'sentence_type' => 'int',
        'image_type'    => 'int',
        'sort'          => 'int',
        'create_time'   => 'datetime',
        'update_time'   => 'datetime'
    ];

    public static function onBeforeInsert($data) {
        if($data -> id === null || $data -> id === '') {
            $data -> id = IDGenerator::getId();
        }
    }
    
    public function getIdAttr($val) {
        return strval($val);
    }
    
    public function getUserIdAttr($val) {
        return strval($val);
    }
    
    public function getLevelIdAttr($val) {
        return strval($val);
    }

    public function scopeId($query, $val) {
        $query -> where('id', $val);
    }

    public static function add($data) {
        try {
            validate(CurrentValidate::class)
                -> scene('add')
                -> check($data);
            self::create($data);
            return Status::ADD_OK();
        } catch (ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    public static function edit($data) {
        try {
            validate(CurrentValidate::class)
                -> scene('edit')
                -> check($data);
            self::update($data, ['id' => $data['id']], ['user_id', 'level_id', 'sentence_type', 'iamge_type', 'sort', 'disabled']);
            return Status::EDIT_OK();
        } catch (ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }
    
}

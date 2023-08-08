<?php

namespace app\model;

use app\common\IDGenerator;
use app\common\Status;
use app\validate\SocialValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Social extends Model
{

    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;
    protected $schema = [
        'id'  => 'string',
        'user_id' => 'string',
        'icon' => 'string',
        'label' => 'string',
        'link' => 'string',
        'disabled' => 'int',
        'sort'    => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    public static function onBeforeInsert($social) {
        if($social -> id === null || $social -> id === '') {
            $social -> id = IDGenerator::getId();
        }
    }

    public function getDisabledAttr($val) {
        if($val > 0) return true;
        else return false;
    }

    public function setDisabledAttr($val) {
        if($val) return 1;
        else return 0;
    }

    /**
     * 新增信息
     */
    public static function add($data) {
        try {
            validate(SocialValidate::class)
                ->scene('add')
                ->check($data);
            self::create($data);
            return Status::GET_OK();
        } catch(ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch(Exception $e) {
            return Status::SERVICE_ERR();
        }
    }

    /**
     * 修改信息
     */
    public static function edit($data) {
        try {
            validate(SocialValidate::class)
                ->scene('edit')
                ->check($data);
            // 过滤字段
            self::update($data, ['id' => $data['id']], ['user_id', 'icon', 'label','link', 'disabled', 'sort']);
            return Status::EDIT_OK();
        } catch(ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch(Exception $e) {
            return Status::SERVICE_ERR();
        }
    }

    public function scopeUserId($query, $userId) {
        $query -> where('user_id', $userId);
    }

    public function scopeDisabled($query, $disabled) {
        $query -> where('disabled', $disabled);
    }
    
}

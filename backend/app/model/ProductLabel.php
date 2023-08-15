<?php

namespace app\model;

use app\common\IDGenerator;
use app\common\Status;
use app\validate\ProductLabelValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class ProductLabel extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    // 设置字段信息
    protected $schema = [
        'id'          => 'string',
        'product_id'  => 'string',
        'icon'        => 'string',
        'label'       => 'string',
        'link'        => 'string',
        'sort'        => 'int',
        'disabled'    => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    public static function onBeforeInsert($data) {
        if($data -> id === null || $data -> id === '') {
            $data -> id = IDGenerator::getId();
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

    public function scopeDisabled($query, $val) {
        $query -> where('disabled', $val);
    }

    public function scopeLabel($query, $val) {
        $query -> where('label', 'like', '%'.$val.'%');
    }

    public function scopePid($query, $val) {
        $query -> where('product_id', $val);
    }

    public static function add($data) {
        try {
            validate(ProductLabelValidate::class)
                -> scene('add')
                -> check($data);
            self::create($data);
            return Status::ADD_OK();
        } catch(ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    public static function edit($data) {
        try {
            validate(ProductLabelValidate::class)
                -> scene('edit')
                -> check($data);
            self::update($data, ['id' => $data['id']], ['product_id', 'icon', 'label', 'link', 'sort', 'disabled']);
            return Status::EDIT_OK();
        } catch(ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    public static function findPid($pid) {
        return self::pid($pid) -> disabled(0) -> order('sort', 'desc') -> select();
    }
}

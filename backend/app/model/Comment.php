<?php

namespace app\model;

use app\common\IDGenerator;
use app\common\Status;
use app\validate\CommentValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Comment extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    // 设置字段信息
    protected $schema = [
        'id'          => 'string',
        'nickname'    => 'string',
        'content'     => 'string',
        'email'       => 'string',
        'ip'          => 'int',
        'address'     => 'string',
        'visible'     => 'int',
        'disabled'    => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];
    protected $append = ['showDate'];

    public static function onBeforeInsert($data) {
        if($data -> id === null || $data -> id === '') {
            $data -> id = IDGenerator::getId();
        }
    }

    public function getIdAttr($val) {
        return strval($val);
    }

    public function getDisabledAttr($val) {
        if($val > 0) return true;
        else return false;
    }

    public function setDisabledAttr($val) {
        if($val) return 1;
        else return 0;
    }

    public function getVisibleAttr($val) {
        if($val > 0) return true;
        else return false;
    }

    public function setVisibleAttr($val) {
        if($val) return 1;
        else return 0;
    }

    public function getShowDateAttr($val, $data) {
        return date('Y-m-d', strtotime($data['update_time']));
    }

    public function scopeDisabled($query, $val) {
        $query -> where('disabled', $val);
    }

    public function scopeVisibled($query, $val) {
        $query -> where('visible', $val);
    }

    public function searchNicknameAttr($query, $value, $data)
    {
        $query->where('nickname','like', '%' . $value . '%');
    }

    public function searchContentAttr($query, $value, $data)
    {
        $query->where('content','like', '%' . $value . '%');
    }

    public function searchEmailAttr($query, $value, $data)
    {
        $query->where('email','like', '%' . $value . '%');
    }

    public function searchAddressAttr($query, $value, $data)
    {
        $query->where('address','like', '%' . $value . '%');
    }

    /**
     * 添加数据
     */
    public static function add($data) {
        try {
            validate(CommentValidate::class)
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

    /**
     * 编辑数据
     */
    public static function edit($data) {
        try {
            validate(CommentValidate::class)
                -> scene('edit')
                -> check($data);
                self::update($data, ['id' => $data['id']], ['id', 'nickname', 'content', 'email', 'visible', 'disabled', 'ip', 'address']);
            return Status::EDIT_OK();
        } catch (ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }
    
}

<?php

namespace app\model;

use app\common\IDGenerator;
use app\common\RegValidate;
use app\common\Status;
use app\validate\UserValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;

class User extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;
    protected $schema = [
        'id'          => 'string',
        'nickname'    => 'string',
        'address'     => 'string',
        'sex'         => 'int',
        'level'       => 'int',
        'year'        => 'int',
        'author'      => 'string',
        'start_time'  => 'datetime',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    public static function onBeforeInsert($user) {
        if($user -> id === null || $user -> id === '') {
            $user -> id = IDGenerator::getId();
        }
    }

    public function getAvatarAttr($val)
    {
        $host = config('common.img_host');
        if(RegValidate::validUrl($val)) {
            return $val;
        }
        return "{$host}{$val}";
    }

    /**
     * 添加用户
     */
    public static function add($data) {
        try {
            validate(UserValidate::class)
            ->scene('add')
            ->check($data);
            self::create($data);
            return new Status(211, '添加成功');
        } catch(ValidateException $e) {
            return new Status(411, $e -> getMessage());
        } catch(Exception $e) {
            return Status::SERVICE_ERR();
        }
    }

    /**
     * 修改用户
     */
    public static function edit($data) {
        try {
            validate(UserValidate::class)
            ->scene('edit')
            ->check($data);
            // 过滤字段
            self::update($data, ['id' => $data['id']], ['nickname', 'address', 'sex', 'level', 'year', 'author', 'start_time']);
            return new Status(213, '修改成功');
        } catch(ValidateException $e) {
            return new Status(413, $e -> getMessage());
        } catch(Exception $e) {
            return Status::SERVICE_ERR();
        }
    }
}

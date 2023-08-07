<?php

namespace app\model;

use app\common\IDGenerator;
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
}

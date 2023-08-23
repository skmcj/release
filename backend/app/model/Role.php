<?php

namespace app\model;
use app\common\IDGenerator;
use app\common\Status;
use app\validate\RoleValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Role extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    // 设置字段信息
    protected $schema = [
        'id'          => 'string',
        'username'    => 'string',
        'password'    => 'string',
        'role'        => 'int',
        'disabled'    => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

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

    public function getRoleTextAttr($val, $data) {
        if($data['role'] === 0) return '所有者';
        elseif($data['role'] === 1) return '管理员';
        else return '游客';
    }

    public function scopeUsername($query, $val) {
        $query -> where('username', $val);
    }

    public function scopeDisabled($query, $val) {
        $query -> where('disabled', $val);
    }
    
    public function scopeId($query, $val) {
        $query -> where('id', $val);
    }

    public static function getById($id) {
        return self::id($id) -> field(['id', 'username', 'role', 'disabled']) -> find();
    }

    public static function add($data) {
        try {
            validate(RoleValidate::class)
                -> scene('add')
                -> check($data);
            // 判断用户是否存在
            $role = self::username($data['username']) -> find();
            if($role !== null) {
                return Status::create(411, '该用户已存在');
            }
            // 添加角色
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
            validate(RoleValidate::class)
                -> scene('edit')
                -> check($data);
            // 判断用户是否存在
            $role = self::id($data['id']) -> find();
            if($role === null) {
                return Status::create(413, '查无该用户');
            }
            if($data['adRole'] >= $role -> role) {
                return Status::create(413, '权限不足，不能修改超过自己权限的角色');
            }
            if(isset($data['username'])) {
                $erole = self::username($data['username']) -> find();
                if($erole !== null) {
                    return Status::create(413, '用户名已存在');
                }
            }
            // 修改角色
            self::update($data, ['id' => $data['id']], ['username', 'password', 'role', 'disabled']);
            return Status::EDIT_OK();
        } catch (ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    public static function login($data) {
        try {
            validate(RoleValidate::class)
                -> scene('login')
                -> check($data);
            // 查找角色
            $role = self::username($data['username']) -> disabled(0) -> find();
            if($role === null) {
                return [
                    'data' => null,
                    'status' => Status::create(420, '用户不存在或被封禁')
                ];
            }
            if(strcmp($role -> password, $data['password']) != 0) {
                return [
                    'data' => null,
                    'status' => Status::create(420, '用户密码错误')
                ];
            }
            return [
                'data' => $role,
                'status' => Status::LOGIN_OK()
            ];
        } catch (ValidateException $e) {
            return [
                'data' => null,
                'status' => Status::create(411, $e -> getMessage())
            ];
        } catch (Exception $th) {
            return [
                'data' => null,
                'status' => Status::SERVICE_ERR()
            ];
        }
    }
    
}

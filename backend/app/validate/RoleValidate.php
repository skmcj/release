<?php

namespace app\validate;

use think\Validate;

class RoleValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'username' => 'require|alphaDash|min:8',
        'password' => 'require|min:8',
        'role' => 'number|between:0,1',
        'disabled' => 'boolean'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'=>'错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => 'ID不能为空',
        'username.require' => '用户名不能为空',
        'username.alphaDash' => '用户名只能由字母、数字、_和-组成',
        'username.min' => '用户名不能低于8个字符',
        'password.require' => '密码不能为空',
        'password.min' => '密码不能低于8个字符',
        'role.number' => '权限标识需为数字',
        'role.max' => '权限标识超限',
        'disabled.boolean' => 'disabled需为布尔值'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['username', 'paddword', 'role', 'disabled'],
        'login' => ['username', 'paddword'],
    ];

    public function sceneEdit() {
        return $this -> only(['id', 'username', 'password', 'role', 'disabled'])
            -> remove('username', 'require')
            -> remove('password', 'require');
    }
}

<?php

namespace app\validate;

use think\Validate;

class CommentValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'nickname' => 'require|max:64',
        'content' => 'require|max:255',
        'email' => 'email',
        'visible' => 'boolean',
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
        'nickname.require' => '昵称不能为空',
        'nickname.max' => '昵称不能超过64个字符',
        'content.require' => '内容不能为空',
        'content.max' => '内容不能超过64个字符',
        'email.email' => '邮箱格式不正确',
        'visible' => 'visible需为布尔值',
        'disabled' => 'disibled需为布尔值'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['nickname', 'content', 'email', 'visible', 'disabled']
    ];

    public function sceneEdit() {
        return $this -> only(['id', 'nickname', 'content', 'email', 'visible', 'disabled'])
            -> remove('nickname', 'require')
            -> remove('content', 'require');
    }
}

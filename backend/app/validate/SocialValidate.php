<?php

namespace app\validate;

use app\common\RegValidate;
use think\Validate;

class SocialValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'userId' => 'require',
        'icon' => 'max:128',
        'label' => 'max:32',
        'link' => 'checkLink',
        'disabled' => 'boolean',
        'sort' => 'number'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'=>'错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => 'ID不能为空',
        'userId.require' => '用户ID不能为空',
        'icon.max' => '图标类不能超过128个字符',
        'label.max' => '标签不能超过32个字符',
        'link.checkLink' => '链接格式错误',
        'disabled.boolean' => 'disabled需为布尔值',
        'sort.number' => 'sort需为数字'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['userId', 'icon', 'label','link', 'disabled', 'sort']
    ];

    /**
     * 修改场景的验证规则
     */
    public function sceneEdit() {
        return $this -> only(['id', 'userId', 'icon', 'label','link', 'disabled', 'sort'])
            -> remove('userId', 'require');
    }

    public function checkLink($val, $rule, $data = []) {
        $flag = RegValidate::validUrl($val);
        return $flag ? $flag : '链接格式错误';
    }
}

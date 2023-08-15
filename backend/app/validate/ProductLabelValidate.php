<?php

namespace app\validate;

use think\Validate;

class ProductLabelValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'icon' => 'max:128',
        'label' => 'max:64',
        'link' => 'max:255',
        'sort' => 'number',
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
        'icon.max' => 'icon不能超过128字符',
        'label.max' => 'label不能超过64个字符',
        'link.max' => 'link不能超过255个字符',
        'sort.number' => 'sort需为number',
        'disabled.boolean' => 'disabled需为布尔值'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['icon', 'label', 'link', 'disabled'],
        'edit' => ['id', 'icon', 'label', 'link', 'sort', 'disabled']
    ];
}

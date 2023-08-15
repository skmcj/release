<?php

namespace app\validate;

use think\Validate;

class ProductValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'          => 'require',
        'name'        => 'require|max:64',
        'tip'         => 'max:255',
        'logo'        => 'max:255',
        'compDate'    => 'dateFormat:Y-m-d',
        'disabled'    => 'boolean'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'=>'错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => 'ID不能为空',
        'name.require' => '作品名称不能为空',
        'name.max' => '作品名称不能超过64个字符',
        'tip.max' => '描述简介不能超错255个字符',
        'logo.max' => 'logo不能超过255个字符',
        'compDate.dateFormat' => '完成日期格式不正确，需为：yyyy-MM-dd',
        'disabled.boolean' => 'disabled需为布尔值'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['name', 'tip', 'logo', 'compDate', 'disabled']
    ];

    public function sceneEdit() {
        return $this -> only(['id', 'name', 'tip', 'logo', 'compDate', 'disabled'])
            -> remove('name', 'require');
    }
}

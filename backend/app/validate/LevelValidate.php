<?php

namespace app\validate;

use think\Validate;

class LevelValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'tip' => 'max:16',
        'labels' => 'array|length:2,12',
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
        'tip.require' => 'tip不能为空',
        'tip.max' => 'tip不能超过16个字符',
        'labels.require' => 'labels不能为空',
        'labels.array' => 'labels需为一个数组',
        'labels.length' => 'labels需有2-12个元素',
        'sort.number' => 'sort需为number'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [];

    public function sceneAdd() {
        return $this -> only(['tip', 'labels', 'sort'])
            -> append('tip', 'require')
            -> append('labels', 'require');
    }
}
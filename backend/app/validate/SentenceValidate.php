<?php

namespace app\validate;

use think\Validate;

class SentenceValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'content' => 'require|max:255',
        'showDate' => 'date',
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
        'content.require' => '内容不能为空',
        'content.max' => '内容不能超过255个字符',
        'showDate.data' => '日期格式无效',
        'disabled.boolean' => 'disabled需为布尔值'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['content', 'showDate', 'disabled']
    ];

    public function sceneEdit() {
        $this -> only(['id', 'content', 'showDate', 'disabled'])
            ->remove('content', 'require');
    }
}

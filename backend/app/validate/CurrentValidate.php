<?php

namespace app\validate;

use think\Validate;

class CurrentValidate extends Validate
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
        'levelId' => 'require',
        'sentenceType' => 'number|between:0,1',
        'imageType' => 'number|between:0,1',
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
        'id.require' => 'ID 不能为空',
        'userId.require' => '用户ID 不能为空',
        'levelId.require' => '境界ID 不能为空',
        'sentenceType.number' => '每日一言类型需为数字',
        'sentenceType.between' => '每日一言类型超限',
        'imageType.number' => '每日一图类型需为数字',
        'imageType.between' => '每日一图类型超限',
        'sort.number' => '排序依据需为数字',
        'disabled.boolean' => 'disabled需为布尔值'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['userId', 'levelId', 'sentenceType', 'iamgeType', 'sort', 'disabled']
    ];

    public function sceneEdit() {
        return $this -> only(['id', 'userId', 'levelId', 'sentenceType', 'iamgeType', 'sort', 'disabled'])
            -> remove('userId', 'require')
            -> remove('levelId', 'require');
    }
}

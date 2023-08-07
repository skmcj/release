<?php

namespace app\validate;

use think\Validate;

class UserValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'       => 'require',
        'nickname' => 'require|max:24',
        'address'  => 'require|max:32',
        'avatar'   => 'require',
        'sex'      => 'number|between:0,2',
        'level'    => 'number|between:0,100',
        'year'     => 'require|number',
        'author'   => 'require|max:24',
        'startTime'=> 'require|date'
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
        'nickname.max' => '昵称最多不能超过24个字符',
        'address.require' => '地址不能为空',
        'address.max' => '地址最多不能超过32个字符',
        'avatar.require' => '头像不能为空',
        'sex.number'  => '性别需为数字[0-未知;1-男;2-女]',
        'sex.between' => '性别指定无效[0-未知;1-男;2-女]',
        'level.number'  => '境界需为数字',
        'level.between' => '境界指定无效[0-100]',
        'year.require' => '年份不能为空',
        'year.number' => '年份需为数字',
        'author.require' => '作者不能为空',
        'author.max' => '作者最多不能超过24个字符',
        'startTime.require' => '开始日期不能为空',
        'startTime.date' => '开始日期格式不正确',
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['nickname', 'address', 'sex', 'level', 'year', 'author', 'startTime']
    ];

    /**
     * 修改场景的验证规则
     */
    public function sceneEdit() {
        return $this -> only(['id', 'nickname', 'address', 'sex', 'level', 'year', 'author', 'startTime'])
            -> remove('nickname', 'require')
            -> remove('address', 'require')
            -> remove('year', 'require')
            -> remove('author', 'require')
            -> remove('startTime', 'require');
    }
}

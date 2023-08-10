<?php

namespace app\validate;

use think\Validate;

class ImageValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'name' => 'max:128',
        'url' => 'require|url|max:256',
        'imageType' => 'in:jpg,png,gif,jpeg,apng,avif,svg,webp,jif,ico,tiff',
        'type' => 'require|in:pc,phone,other',
        'labels' => 'array|length:0,12',
        'w' => 'number',
        'h' => 'number',
        'size' => 'number',
        'disabled' => 'boolean',
        'showDate' => 'dateFormat:Y-m-d'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'=>'错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => 'ID不能为空',
        'name.max' => '名称不能超过128个字符',
        'url.require' => 'url不能为空',
        'url.url' => 'url格式不正确，请输入合法的url格式',
        'url.max' => 'url不能超过255个字符',
        'imageType.in' => '图片类型不支持',
        'type.require' => '显示类型不能为空',
        'type.in' => '显示类型不支持[pc, phone, other]',
        'labels.array' => '标签组需为一个数组',
        'labels.length' => '标签不能超过12个',
        'w.number' => '图片宽度需为数字',
        'h.number' => '图片高度需为数字',
        'size.number' => '图片大小需为数字',
        'disabled.boolean' => '禁用标识需为布尔值',
        'showDate.dateFormat' => '显示日期格式不正确，需为yyyy-MM-dd'
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['name', 'url', 'imageType', 'type', 'labels', 'w', 'h', 'size', 'disabled', 'showDate']
    ];

    public function sceneEdit() {
        return $this -> only(['id', 'name', 'url', 'imageType', 'type', 'labels', 'w', 'h', 'size', 'disabled', 'showDate'])
            -> remove('url', 'require')
            -> remove('type', 'require');
    }
}

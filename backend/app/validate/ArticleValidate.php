<?php

namespace app\validate;

use think\Validate;

class ArticleValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'=>['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'title' => 'require|max:64',
        'path' => 'max:255',
        'cate' => 'max:64',
        'cover' => 'max:255',
        'tags' => 'array|length:0,16',
        'description' => 'max:255',
        'disabled' => 'boolean',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'=>'错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => 'ID不能为空',
        'title.require' => '标题不能为空',
        'title.max' => '标题不能超过64个字符',
        'path.max' => '路径不能超过255个字符',
        'cate.max' => '类别不能超过64个字符',
        'cover.max' => '封面不能超过255个字符',
        'tags.array' => '标签需为一个数组',
        'tags.length' => '标签不能16个',
        'description.max' => '描述不能超过255个字符',
        'disabled.boolean' => 'disabled需为布尔值',
    ];
    /**
     * 定义验证场景
     * 格式：'场景名'=>['规则1','规则2',...]
     *
     * @var array
     */
    protected $scene = [
        'add' => ['title', 'path', 'cate', 'cover', 'tags', 'description', 'disabled'],
        'count' => ['id', 'count']
    ];

    public function sceneEdit() {
        return $this -> only(['id', 'title', 'path', 'cate', 'cover', 'tags', 'description', 'disabled'])
            -> remove('title', 'require');
    }
}

<?php
namespace app\model;
use think\Model;

class Students extends Model
{
    protected $pk = 'sno';
    // 设置字段信息
    protected $schema = [
        'sno'          => 'string',
        'sname'        => 'string',
        'sex'      => 'string',
        'age'       => 'int'
    ];
}


<?php

namespace app\model;
use think\Model;
class Image extends Model
{
    // protected $table = 'image';

    // 设置字段信息
    protected $schema = [
        'id'          => 'string',
        'url'         => 'string',
        'image_type'  => 'string',
        'type'        => 'int',
        'labels'      => 'string',
        'w'           => 'int',
        'h'           => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];
    
}

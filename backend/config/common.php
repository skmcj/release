<?php

return [
    // 服务器存储图片host
    'img_host'     => 'http://localhost:8080/oss/',
    // 上传图片临时host
    'img_tmp'      => 'http://localhost:8080/tmp/',
    // 临时图片正则判断
    'img_tmp_reg'  => '/^https?:\/\/localhost:8080\/tmp\//',
    // 临时图片名称正则提取
    'img_tmpn_reg'  => '/^https?:\/\/localhost:8080\/tmp\/(.*?)$/',
    // 本地存储文件夹
    'storage_dir'  => 'public/storage/',
    // 缓存文件夹
    'tmp_dir'      => 'public/tmp/',
    // 资源地址
    'resource'     => base_path() . 'resource/',
    // 文章路径
    'article_dir'  => base_path() . 'resource/article/',
    // 文章访问host
    'article_host'  => 'http://localhost:8080/article/',
    // ip数据库文件地址
    'ipdb'         => base_path() . 'resource/data/ip2region.xdb'
];
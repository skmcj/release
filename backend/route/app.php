<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

// User 相关 api
Route::get('user/all/:id', 'User/getAllById');
Route::get('user/list', 'User/getList');
Route::get('user/page', 'User/getPage');
Route::get('user/:id', 'User/getById');
Route::delete('user/:id', 'User/deleteById');
Route::post('user', 'User/save');
Route::put('user', 'User/update');


// 用户社交信息 相关 api
Route::get('social/user/:id', 'Social/getByUserId');
Route::get('social/link/:id', 'Social/getLinkByUserId');
Route::get('social/all', 'Social/getAll');
Route::get('social/:id', 'Social/getById');
Route::post('social', 'Social/save');
Route::put('social', 'Social/edit');
Route::delete('social/:id', 'Social/deleteById');

// 境界标签相关
Route::get('level/list', 'Level/getList');
Route::get('level/page', 'Level/getPage');
Route::get('level/:id', 'Level/getById');
Route::post('level', 'Level/save');
Route::put('level', 'Level/edit');
Route::delete('level/:id', 'Level/deleteById');

// 每日一句
Route::get('sentence/page', 'Sentence/getPage');
Route::get('sentence/random', 'Sentence/getRandom');
Route::get('sentence/daily', 'Sentence/getDaily');
Route::get('sentence/:id', 'Sentence/getById');
Route::post('sentence/batch', 'Sentence/saveBatch');
Route::post('sentence', 'Sentence/save');
Route::put('sentence', 'Sentence/edit');
Route::delete('sentence', 'Sentence/deleteByIds');

// 每日一图
Route::get('image/page', 'Image/getPage');
Route::get('image/random', 'Image/getRandom');
Route::get('image/daily', 'Image/getDaily');
Route::get('image/:id', 'Image/getById');
Route::post('image/batch', 'Image/saveBatch');
Route::post('image', 'Image/save');
Route::put('image', 'Image/edit');
Route::delete('image', 'Image/deleteByIds');

// 留言路由


// 其它路由
Route::get('ipinfo', 'Other/getIpInfo');

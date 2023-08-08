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

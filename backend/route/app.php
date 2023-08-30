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
Route::get('user/list', 'User/getList') -> middleware('check');
Route::get('user/page', 'User/getPage') -> middleware('check');
Route::get('user/:id', 'User/getById');
Route::delete('user/:id', 'User/deleteById') -> middleware('checkAdmin');
Route::post('user', 'User/save') -> middleware('checkAdmin');
Route::put('user', 'User/update') -> middleware('checkAdmin');


// 用户社交信息 相关 api
Route::get('social/user/:id', 'Social/getByUserId') -> middleware('check');
Route::get('social/link/:id', 'Social/getLinkByUserId');
Route::get('social/all', 'Social/getAll') -> middleware('check');
Route::get('social/:id', 'Social/getById') -> middleware('check');
Route::post('social', 'Social/save') -> middleware('check');
Route::put('social', 'Social/edit') -> middleware('check');
Route::delete('social/:id', 'Social/deleteById') -> middleware('check');

// 境界标签相关
Route::get('level/list', 'Level/getList') -> middleware('check');
Route::get('level/page', 'Level/getPage') -> middleware('check');
Route::get('level/:id', 'Level/getById');
Route::post('level', 'Level/save') -> middleware('check');
Route::put('level', 'Level/edit') -> middleware('check');
Route::delete('level/:id', 'Level/deleteById') -> middleware('check');

// 每日一句
Route::get('sentence/page', 'Sentence/getPage') -> middleware('check');
Route::get('sentence/random', 'Sentence/getRandom');
Route::get('sentence/daily', 'Sentence/getDaily');
Route::get('sentence/:id', 'Sentence/getById');
Route::post('sentence/batch', 'Sentence/saveBatch') -> middleware('check');
Route::post('sentence', 'Sentence/save') -> middleware('check');
Route::put('sentence', 'Sentence/edit') -> middleware('check');
Route::delete('sentence', 'Sentence/deleteByIds') -> middleware('check');


// 每日一图
Route::get('image/size', 'Image/getImageSize') -> cache(3600);
Route::get('image/page', 'Image/getPage') -> middleware('check');
Route::get('image/random', 'Image/getRandom');
Route::get('image/daily', 'Image/getDaily');
Route::get('image/:id', 'Image/getById');
Route::post('image/batch', 'Image/saveBatch') -> middleware('check');
Route::post('image', 'Image/save') -> middleware('check');
Route::put('image', 'Image/edit') -> middleware('check');
Route::delete('image', 'Image/deleteByIds') -> middleware('check');


// 留言路由
Route::get('comment/page', 'Comment/getPage') -> middleware('check');
Route::get('comment/:id', 'Comment/getById');
Route::get('comment', 'Comment/getComment');
Route::post('comment/bk', 'Comment/saveBK') -> middleware('check');
Route::put('comment/bk', 'Comment/editBK') -> middleware('check');
Route::post('comment', 'Comment/save');
Route::put('comment', 'Comment/edit') -> middleware('check');
Route::delete('comment/:id', 'Comment/deleteById') -> middleware('check');

// 作品路由
Route::get('product', 'Product/getPageFront');
Route::get('product/page', 'Product/getPage') -> middleware('check');
Route::get('product/list', 'Product/getIDList') -> middleware('check');
Route::get('product/:id', 'Product/getById');
Route::post('product', 'Product/save') -> middleware('check');
Route::put('product', 'Product/edit') -> middleware('check');
Route::delete('product/:id', 'Product/deleteById') -> middleware('check');

// 标签
Route::get('plabel/page', 'ProductLabel/getPage') -> middleware('check');
Route::post('plabel', 'ProductLabel/save') -> middleware('check');
Route::put('plabel', 'ProductLabel/edit') -> middleware('check');
Route::delete('plabel/:id', 'ProductLabel/deleteById') -> middleware('check');

// 文章
Route::get('article/page', 'Article/getPage') -> middleware('check');
Route::get('article/list', 'Article/getList') -> middleware('check');
Route::get('article/id/:id', 'Article/getById') -> middleware('check');
Route::get('article/:name', 'Article/getProfile');
Route::post('article', 'Article/save') -> middleware('check');
Route::put('article/edit', 'Article/editArticle') -> middleware('check');
Route::put('article', 'Article/edit') -> middleware('check');
Route::delete('article/:id', 'Article/deleteById') -> middleware('check');

// 角色路由
Route::get('role', 'Role/getPage') -> middleware('check');
Route::get('role/check', 'Role/checkToken') -> middleware('check');
Route::get('role/:id', 'Role/getById') -> middleware('check');
Route::post('role', 'Role/save') -> middleware('checkOwner');
Route::post('login', 'Role/login');
Route::post('logout', 'Role/logout') -> middleware('check');
Route::put('role', 'Role/edit') -> middleware('check');
Route::delete('role/:id', 'Role/deleteById') -> middleware('check');

// current
Route::get('current', 'Current/getCurrent');
Route::get('current/page', 'Current/getPage') -> middleware('check');
Route::get('current/:id', 'Current/getById') -> middleware('check');
Route::post('current', 'Current/save') -> middleware('check');
Route::put('current', 'Current/edit') -> middleware('check');
Route::delete('current/:id', 'Current/deleteById') -> middleware('check');


// 其它路由
Route::get('ipinfo', 'Other/getIpInfo');

<?php

namespace app\controller;

use app\BaseController;
use app\common\CommonUtil;
use app\common\RegValidate;
use app\common\Status;
use app\model\Article;
use app\pojo\PageEntity;
use Exception;
use think\facade\View;

class ArticleController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 获取文章视图
     */
    public function getProfile($name) {

      // TODO: 使用 composer require symfony/yaml 实现md文档内的自定义配置
      /**
       * 在md文档标头用yaml语法写一些预设允许的自定义配置项，如：
       * ---
       * title: 一个测试文档
       * tags:
       *  - 标签
       * cover: 图片链接
       * description: 解释简介
       * ---
       * 开始时将以上配置从源文档分类解析处理
       */
      if(RegValidate::validUrlCode($name)) {
          $name = urldecode($name);
      }
      $theme = $this -> request -> cookie('theme');
      try {
        // 获取文章信息
        $article = Article::title($name) -> find();
        $title = $name;
        $cover = '';
        if(!empty($article -> cover)) {
          $cover = $article -> cover;
        } else {
          $cover = CommonUtil::getImageDaily();
        }
        if(!empty($article -> title)){
          $title = $article -> title;
        } else {
          $title = $name;
        }
        if(!empty($article -> description)) {
          View::assign('tip', $article -> description);
        }
        $text = file_get_contents(config('common.resource')."article/{$name}.md");
        View::assign('theme', $theme === 'dark' ? 'dark' : '');
        View::assign('themeClass', $theme === 'dark' ? 'editormd-preview-theme-dark' : '');
        View::assign('title', $title);
        View::assign('cover', $cover);
        View::assign('main', $text);
        return View::fetch();
      } catch (Exception $th) {
        View::assign('theme', $theme === 'dark' ? 'dark' : '');
        return View::fetch('notfound');
      }
        
    }

    public function getList() {
      $list = Article::disabled(0) -> scope('sm') -> order('update_time', 'desc') -> select();
      return result()::success($list, Status::GET_OK());
    }

    public function getPage($page = 1, $pageSize = 5, $key = '') {
      $query = new Article();
      if($key !== '') {
          $query = Article::title($key);
      }
      $list = $query -> page($page, $pageSize) -> select();
      $total = $query -> count();
      if($list === null || count($list) <= 0) return result()::error(Status::GET_ERR());
      $data = new PageEntity($list, $total, $page, $pageSize);
      return result()::success($data, Status::GET_OK());
    }

    public function getById($id) {
      $data = Article::find($id);
      if($data === null) return result()::error(Status::GET_ERR());
      return result()::success($data, Status::GET_OK());
    }

    /**
     * 获取文章内容
     */
    public function getContentById($id) {
      $article = Article::id($id) -> find();
      if($article === null) return result()::error(Status::GET_ERR('文章不存在'));
      $res = CommonUtil::readArticle($article -> getData('path'));
      if(!$res) return result()::error(Status::GET_ERR('文章获取失败'));
      return result()::success($res, Status::GET_OK());
    }

    /**
     * 新增文章
     */
    public function save() {
      $status = Article::add($this -> request -> post());
      return result()::success(null, $status);
    }

    /**
     * 修改文章
     */
    public function edit() {
      $status = Article::edit($this -> request -> put());
      return result()::success(null, $status);
    }

    /**
     * 编辑文章
     */
    public function editArticle() {
      $data = $this -> request -> put();
      $id = $data['id'];
      $content = $data['content'];
      $article = Article::find($id);
      if($article === null) {
        return result()::error(Status::EDIT_ERR());
      }
      $edata = CommonUtil::editArticle($article -> getData('path'), $content);
      Article::editCount($id, $edata['count']);
      return result()::success($edata['count'], Status::EDIT_OK());
    }

    public function deleteById($id) {
      $status = Article::deleteById($id);
      return result()::success(null, $status);
    }
}

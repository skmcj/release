<?php

namespace app\controller;

use app\BaseController;
use app\common\Status;
use Exception;
use think\facade\View;

class ArticleController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

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
      $theme = $this -> request -> cookie('theme');
      try {
        $text = file_get_contents(config('common.resource')."article/{$name}.md");
        View::assign('theme', $theme === 'dark' ? 'dark' : '');
        View::assign('themeClass', $theme === 'dark' ? 'editormd-preview-theme-dark' : '');
        View::assign('title', $name);
        View::assign('main', $text);
        return View::fetch();
      } catch (Exception $th) {
        View::assign('theme', $theme === 'dark' ? 'dark' : '');
        return View::fetch('notfound');
      }
        
    }
}

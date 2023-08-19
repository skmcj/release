<?php

namespace app\model;

use app\common\CommonUtil;
use app\common\IDGenerator;
use app\common\RegValidate;
use app\common\Status;
use app\validate\ArticleValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Article extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    // 设置字段信息
    protected $schema = [
        'id'          => 'string',
        'title'       => 'string',
        'path'        => 'string',
        'cate'        => 'string',
        'tags'        => 'string',
        'cover'       => 'string',
        'description' => 'string',
        'count'       => 'int',
        'eyes'        => 'int',
        'disabled'    => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    protected $type = [
        'tags' => 'array'
    ];

    public static function onBeforeInsert($data) {
        if($data -> id === null || $data -> id === '') {
            $data -> id = IDGenerator::getId();
        }
    }
    
    public function getIdAttr($val) {
        return strval($val);
    }

    public function getDisabledAttr($val) {
        if($val > 0) return true;
        else return false;
    }

    public function setDisabledAttr($val) {
        if($val) return 1;
        else return 0;
    }

    public function getPathAttr($val) {
        return config('common.article_host').$val;
    }
    
    public function getCoverAttr($val) {
        if(RegValidate::validUrl($val)) return $val;
        else return config('common.img_host').$val;
    }

    public function scopeDisabled($query, $val) {
        $query -> where('disabled', $val);
    }
    
    /**
     * 获取缩略信息
     */
    public function scopeSm($query) {
        $query -> field('id, title');
    }

    public function scopeTitle($query, $val) {
        $query -> where('title', 'like', '%'.$val.'%');
    }

    /**
     * 新增文章
     */
    public static function add($data) {
        try {
            validate(ArticleValidate::class)
                -> scene('add')
                -> check($data);
            if(!empty($data['cover'])) {
                // 如果不为网络图片
                if(!RegValidate::validUrl($data['cover'])) {
                    $img = CommonUtil::saveImageByTmp($data['cover']);
                    $data['cover'] = $img -> getName();
                }
            }
            // 创建文章文件，以title命名
            $article = CommonUtil::editArticle($data['title'], "# {$data['title']}\n");

            $data['path'] = $article['name'];
            $data['count'] = $article['count'];
            self::create($data);
            return Status::ADD_OK();
        } catch(ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    public static function editCount($id, $count) {
        try {
            self::update([
                'id' => $id,
                'count' => $count
            ], ['id' => $id], ['count']);
            return Status::EDIT_OK();
        } catch (Exception $th) {
            return Status::EDIT_ERR();
        } 
    }

    public static function edit($data) {
        try {
            validate(ArticleValidate::class)
                -> scene('edit')
                -> check($data);
            
            if(!empty($data['title'])) {
                $article = self::find($data['id']);
                $newPath = CommonUtil::renameArticle($article -> getData('path'), $data['title']);
                $data['path'] = $newPath;
            }
            self::update($data, ['id' => $data['id']], ['title', 'path', 'cate', 'cover', 'tags', 'description', 'disabled']);
            return Status::EDIT_OK();
        } catch(ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        } 
    }


    public static function deleteById($id) {
        $list = Product::articleId($id) -> select();
        if(count($list) > 0) return Status::create(412, '该文章还存在绑定的作品，不可删除！');
        $flag = self::destroy($id);
        if(!$flag) return Status::DEL_ERR();
        return Status::DEL_OK();
    }
    
}

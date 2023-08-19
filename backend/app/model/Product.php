<?php

namespace app\model;

use app\common\CommonUtil;
use app\common\IDGenerator;
use app\common\RegValidate;
use app\common\Status;
use app\validate\ProductValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Product extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    // 设置字段信息
    protected $schema = [
        'id'          => 'string',
        'article_id'  => 'string',
        'name'        => 'string',
        'tip'         => 'string',
        'logo'        => 'string',
        'comp_date'   => 'datetime',
        'stars'       => 'string',
        'disabled'    => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    protected $type = [
        'comp_date' => 'datetime:Y-m-d'
    ];

    protected $append = [
        'logoUrl'
    ];

    public static function onBeforeInsert($data) {
        if($data -> id === null || $data -> id === '') {
            $data -> id = IDGenerator::getId();
        }
    }
    
    public function getIdAttr($val) {
        return strval($val);
    }
    
    public function getArticleIdAttr($val) {
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

    public function getLogoUrlAttr($val, $data) {
        if(empty($data['logo'])) return '';
        $logo = $data['logo'];
        $host = config('common.img_host');
        if(RegValidate::validUrl($logo)) {
            return $logo;
        }
        return "{$host}{$logo}";
    }

    public function scopeSm($query) {
        $query -> field('id, name');
    }

    public function scopeArticleId($query, $val) {
        $query -> where('article_id', $val);
    }

    public function scopeSearchName($query, $val) {
        $query -> where('name', 'like', '%'.$val.'%');
    }

    public function scopeDisabled($query, $val) {
        $query -> where('disabled', $val);
    }

    /**
     * 新增作品
     */
    public static function add($data) {
        try {
            validate(ProductValidate::class)
                -> scene('add')
                -> check($data);
            if(!empty($data['logo'])) {
                $img = CommonUtil::saveImageByTmp($data['logo']);
                $data['logo'] = $img -> getName();
            }
            self::create($data);
            return Status::ADD_OK();
        } catch(ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }

    }

    /**
     * 修改作品
     */
    public static function edit($data) {
        try {
            validate(ProductValidate::class)
                -> scene('edit')
                -> check($data);
            if(!empty($data['logo'])) {
                $img = CommonUtil::saveImageByTmp($data['logo']);
                $data['logo'] = $img -> getName();
            }
            self::update($data, ['id' => $data['id']], ['article_id', 'name', 'tip', 'logo', 'comp_date', 'stars', 'disabled']);
            return Status::EDIT_OK();
        } catch(ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }

    }
    
}

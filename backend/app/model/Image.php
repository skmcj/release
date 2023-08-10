<?php

namespace app\model;

use app\common\IDGenerator;
use app\common\Status;
use app\validate\ImageValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Image extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    // 设置字段信息
    protected $schema = [
        'id'          => 'string',
        'name'        => 'string',
        'url'         => 'string',
        'image_type'  => 'string',
        'type'        => 'int',
        'labels'      => 'string',
        'w'           => 'int',
        'h'           => 'int',
        'size'        => 'int',
        'disabled'    => 'int',
        'show_date'   => 'datetime',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];
    protected $type = [
        'show_date' => 'datetime:Y-m-d',
        'labels' => 'array'
    ];

    public static function onBeforeInsert($data) {
        if($data -> id === null || $data -> id === '') {
            $data -> id = IDGenerator::getId();
        }
    }

    public function getDisabledAttr($val) {
        if($val > 0) return true;
        else return false;
    }

    public function setDisabledAttr($val) {
        if($val) return 1;
        else return 0;
    }

    public function getTypeAttr($val) {
        if($val === 0) return 'pc';
        else if($val === 1) return 'phone';
        else return 'other';
    }

    public function setTypeAttr($val) {
        if($val === 'pc') return 0;
        else if ($val === 'phone') return 1;
        else return 2;
    }

    public function scopeShowDate($query, $val) {
        $query -> where('show_date', $val);
    }

    public function scopeDisabled($query, $val) {
        $query -> where('disabled', $val);
    }
    
    public function scopeType($query, $value) {
        $val = $value === 'pc' ? 0 : ($value === 'phone' ? 1 : 2);
        $query -> where('type', $val);
    }

    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name','like', '%' . $value . '%');
    }

    public function searchLabelsAttr($query, $value, $data)
    {
        $query->where('labels','like', '%' . $value . '%');
    }

    public function searchTypeAttr($query, $value, $data)
    {
        $val = $value === 'pc' ? 0 : ($value === 'phone' ? 1 : 2);
        $query->where('type', $val);
    }

    public function searchImageTypeAttr($query, $value, $data)
    {
        $query->where('image_type', $value);
    }

    /**
     * 包装数据
     */
    private static function packData($data) {
        if(!empty($data['url']) && empty($data['imageType'])) {
            $data['imageType'] = pathinfo($data['url'], PATHINFO_EXTENSION);
            return $data;
        }
        
        return $data;
    }
    
    /**
     * 新增数据
     */
    public static function add($data) {
        try {
            validate(ImageValidate::class)
                -> scene('add')
                -> check($data);
            $data = self::packData($data);
            self::create($data);
            return Status::ADD_OK();
        } catch(ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    /**
     * 批量添加
     */
    public static function batch(array $data) {
        $i = 0;
        try {
            $list = [];
            foreach($data as $item) {
                $i ++;
                validate(ImageValidate::class)
                    ->scene('add')
                    ->check($item);
                $img = self::packData($item);
                array_push($list, $img);
            }
            // TODO: 可用拼接sql优化
            $image = new Image();
            $image -> saveAll($list);
            return Status::ADD_OK();
        } catch (ValidateException $e) {
            return Status::create(411, "第{$i}项：".$e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }

    /**
     * 修改
     */
    public static function edit($data) {
        try {
            validate(ImageValidate::class)
                ->scene('edit')
                ->check($data);
            $data = self::packData($data);
            self::update($data, ['id' => $data['id']], ['name', 'url', 'image_type', 'type', 'labels', 'w', 'h', 'size', 'disabled', 'show_date']);
            return Status::EDIT_OK();
        } catch (ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }
}

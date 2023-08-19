<?php

namespace app\model;
use app\common\IDGenerator;
use app\common\Status;
use app\validate\SentenceValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;
class Sentence extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;
    protected $schema = [
        'id' => 'string',
        'content' => 'string',
        'show_date' => 'string',
        'disabled' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    protected $type = [
        'show_date' => 'datetime:Y-m-d'
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

    public function scopeShowDate($query, $val) {
        $query -> where('show_date', $val);
    }

    public function scopeDisabled($query, $val) {
        $query -> where('disabled', $val);
    }

    public function searchContentAttr($query, $value, $data) {
        $query -> where('content', 'like', '%'.$value.'%');
    }

    /**
     * 添加
     */
    public static function add($data) {
        try {
            validate(SentenceValidate::class)
                ->scene('add')
                ->check($data);
            self::create($data);
            return Status::ADD_OK();
        } catch (ValidateException $e) {
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
            foreach($data as $item) {
                $i ++;
                validate(SentenceValidate::class)
                    ->scene('add')
                    ->check($item);
            }
            // TODO: 可用拼接sql优化
            $sentence = new Sentence();
            $sentence -> saveAll($data);
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
            validate(SentenceValidate::class)
                ->scene('edit')
                ->check($data);
            self::update($data, ['id' => $data['id']], ['content', 'disabled', 'show_date']);
            return Status::EDIT_OK();
        } catch (ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch (Exception $th) {
            return Status::SERVICE_ERR();
        }
    }
    
}

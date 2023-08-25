<?php

namespace app\model;

use app\common\CommonUtil;
use app\common\IDGenerator;
use app\common\RegValidate;
use app\common\Status;
use app\validate\UserValidate;
use Exception;
use think\exception\ValidateException;
use think\Model;

class User extends Model
{
    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;
    protected $schema = [
        'id'          => 'string',
        'nickname'    => 'string',
        'address'     => 'string',
        'avatar'      => 'string',
        'sex'         => 'int',
        'level'       => 'int',
        'year'        => 'int',
        'author'      => 'string',
        'start_time'  => 'datetime',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];

    protected $type = [
        'days' => 'int',
        'social' => 'json'
    ];

    public static function onBeforeInsert($user) {
        if($user -> id === null || $user -> id === '') {
            $user -> id = IDGenerator::getId();
        }
    }
    
    public function getIdAttr($val) {
        return strval($val);
    }

    public function getAvatarAttr($val)
    {
        $host = config('common.img_host');
        if(RegValidate::validUrl($val)) {
            return $val;
        }
        return "{$host}{$val}";
    }

    /**
     * 获取用户缩略信息
     */
    public function scopeSm($query) {
        $query -> field('id, nickname');
    }

    /**
     * 添加用户
     */
    public static function add($data) {
        try {
            validate(UserValidate::class)
            ->scene('add')
            ->check($data);
            if(isset($data['avatar'])) {
                $imgName = self::checkAvatar($data['avatar']);
                if($imgName !== null) {
                    $data['avatar'] = $imgName;
                }
            }
            self::create($data);
            return Status::ADD_OK();
        } catch(ValidateException $e) {
            return Status::create(411, $e -> getMessage());
        } catch(Exception $e) {
            return Status::SERVICE_ERR();
        }
    }

    /**
     * 修改用户
     */
    public static function edit($data) {
        try {
            validate(UserValidate::class)
            ->scene('edit')
            ->check($data);

            if(isset($data['avatar'])) {
                $imgName = self::checkAvatar($data['avatar']);
                if($imgName !== null) {
                    $data['avatar'] = $imgName;
                }
            }
            // 过滤字段
            self::update($data, ['id' => $data['id']], ['nickname', 'address', 'avatar', 'sex', 'level', 'year', 'author', 'start_time']);
            return Status::EDIT_OK();
        } catch(ValidateException $e) {
            return Status::create(413, $e -> getMessage());
        } catch(Exception $e) {
            return Status::SERVICE_ERR();
        }
    }

    private static function checkAvatar($avatar) {
        if(RegValidate::validTemp($avatar)) {
            $img = CommonUtil::saveImageByTmpUrl($avatar);
            return $img -> getName();
        }
        return null;
    }
}

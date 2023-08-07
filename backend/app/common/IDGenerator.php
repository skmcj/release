<?php

namespace app\common;


use app\common\snowflake\Snowflake;

/**
 * ID创造器
 */
class IDGenerator {
    private static $generator = null;

    /**
     * 初始化类
     */
    private static function initialize() {
        if(self::$generator === null) {
            self::$generator = new Snowflake();
            // 设置初始时间
            self::$generator -> setStartTimeStamp(strtotime('2023-07-07')*1000);
        }
    }

    /**
     * 使用雪花算法获取ID
     */
    public static function getId() {
        self::initialize();
        return self::$generator -> id();
    }
}
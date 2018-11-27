<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/21
 * Time: 11:06
 */

namespace Driver\Cache;
use think\facade\Config;

class Redis
{
    # 读写分离
     private static $write_handle = null; // 写操作
     private static $read_handel = null;  // 读操作
     public static function getWrite()
     {
        $option = array(
          'host' => Config::get('cache.REDIS_W_HOST') ?? '127.0.0.1',
            'port' => Config::get('cache.REDIS_W_PORT') ?? 6379,
        );
        # 判断是否初始化
        if(!self::$write_handle){
            self::$write_handle = new \Redis();
            //self::$write_handle->connect($option['127.0.0.1'],$option['6379']);
            self::$write_handle->connect($option['host'],$option['port']);
     }
        return self::$write_handle;
     }
     public static function getRead()
     {
         $option = array(
             'host' => Config::get('cache.REDIS_R_HOST') ?? '127.0.0.1',
             'port' => Config::get('cache.REDIS_R_PORT') ?? 6379,
         );
         # 判断是否初始化
         if(!self::$read_handel){
             self::$read_handel = new \Redis();
             self::$read_handel->connect($option['host'],$option['port']);
         }
         return self::$read_handel;
     }
     # 封装redis设置key过期
    public static function setKeyTime($key,$time = 0)
    {
        if(!self::getWrite()) return false;
        return self::getWrite()->expire($key,$time);
    }
    # 封装redis赋值操作 String
     public static function set($key, $value, $expire=0)
     {
         # 先判断是否初始化
         if(!self::getWrite()) return false;
         # 判断是否要设置为有效期间值
         if($expire == 0){
             return self::getWrite()->set($key, $value);
         }else{
             return self::getWrite()->setex($key,$expire,$value);
         }
     }
     # 封装redis取值操作 String
     public static function get($key)
     {
         # 先判断是否是数组
         $func = is_array($key)?'mget':'get';
         return self::getRead()->{$func}($key);
     }
     # 封装redis递增操作 String
     public static function incr($key, $num=1)
     {
         # 先判断初始化
         if(!self::getWrite()) return false;
         # 默认递增为1
         if($num == 1){
             return self::getWrite()->incr($key);
         }else{
             return self::getWrite()->incrBy($key,$num);
         }
     }
     # 封装redis获取字符串长度 String
     public static function strlen($key)
     {
         return self::getWrite()->strlen($key);
     }
     # 封装redis赋值Hash操作
    public static function hset($key,$file,$value)
    {
        if(!self::getWrite()) return false;
        return self::getWrite()->hSet($key,$file,$value);
    }
    # 封装redis多个赋值Hash操作
    public static function hMset($key,$arr)
    {
        # 先判断初始化
        if(!self::getWrite()) return false;
        # 判断是不是数组
        if(!is_array($arr)) return false;
        return self::getWrite()->hMset($key,$arr);
    }
    # 封装redis获取Hash操作
    public static function hget($key,$filed = '')
    {
        # 判断是否要获取单个字段或者全部字段的值
        if(empty($filed)){
            return self::getRead()->hGetAll($key);
        }else{
            return self::getRead()->hGet($key,$filed);
        }
    }
    # 封装redis获取Hash字段长度操作
    public static function hlen($key)
    {
        return self::getRead()->hLen($key);
    }
    # 封装redis Hash自增操作
    public static function hincrby($key,$field,$num = 1)
    {
        return self::getWrite()->hIncrBy($key,$field,$num);
    }
    # 封装redis List左添加
    public static function lpush($key,$value)
    {
        if(!self::getWrite()) return false;
        return self::getWrite()->lPush($key,$value);
    }
    # 封装redis List右弹出
    public static function rpop($key)
    {
        //if(!self::getWrite()) return false;
        return self::getRead()->rPop($key);
    }
    # 封装redis 获取List长度
    public static function llen($key)
    {
        // if(!self::getWrite()) return false;
        return self::getRead()->lLen($key);
    }
    # 封装redis Set添加值
    public static function sadd($key,$value)
    {
        if(!self::getWrite()) return false;
        return self::getWrite()->sAdd($key,$value);
    }
    # 封装redis 获取Set所有数组
    public static function smembers($key)
    {
        return self::getRead()->sMembers($key);
    }
    # 封装redis 获取Set差集
    public static function sdiff($key1,$key2)
    {
        return self::getRead()->sDiff($key1,$key2);
    }
    # 封装redis 获取Set交集
    public static function sinter($key1,$key2)
    {
        return self::getRead()->sInter($key1,$key2);
    }
    # 封装redis 获取Set并集
    public static function sunion($key1,$key2)
    {
        return self::getRead()->sUnion($key1,$key2);
    }
    # 封装redis 获取ZSet赋值
    public static function zadd($key,$score,$value)
    {
        if(!self::getWrite()) return false;
        return self::getWrite()->zAdd($key,$score,$value);
    }
    # 封装redis 获取ZSet获取长度
    public static function zsize($key)
    {
        return self::getRead()->zSize($key);
    }
    # 封装redis 获取ZSet范围
    public static function zrevrange($key,$start,$end,$withscore=true)
    {
        return self::getRead()->zRevRange($key,$start,$end,$withscore);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/28
 * Time: 10:20
 */

namespace app\api\service;


use Driver\Cache\Redis;

class RedisService
{
    # 从redis里面读取产品点击量 把数组
    public static function getOnRedisProductNum()
    {
        $product = Redis::hget('OnClickNum');
        return $product;
    }
}
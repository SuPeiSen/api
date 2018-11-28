<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/28
 * Time: 10:20
 */

namespace app\api\service;


use app\api\model\NewsList;
use Driver\Cache\Redis;

class RedisService
{
    # 从redis里面读取产品点击量 把数组
    public function getOnRedisProductNum()
    {
        $product = Redis::hget('OnClickNum');
        $result = $this->getProductName($product);
        return $result;
    }
    # 根据id查找出对应产品的详情
    private  function getProductName($product)
    {
        # 先把所有id组成一个数组
        $productID_arr= array();
        $productID_num= array();
        foreach ($product as $key => $value){
            $product_id = intval(str_replace("ProductID_","",$key));
            array_push($productID_arr,$product_id);
            array_push($productID_num,$value);
        }
        $model = new NewsList();
        $product_name = $model->where('newsid','in',$productID_arr)
            ->select()
            ->visible(['title'])
            ->toArray();
        return [$productID_arr,$product_name,$productID_num];
    }
}
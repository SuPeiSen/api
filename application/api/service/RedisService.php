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
    # 从redis-hash里面读取产品id，title，num
    public function getOnRedisProductNum()
    {
        $productAll = Redis::hget('OnClickNum');
        $result = $this->getProductArray($productAll);
        return $result;
    }
    # 组装一个包含title，id， num数组
    private function getProductArray($productAll)
    {
        $newProductAll = array();
        foreach ($productAll as $key => $value){
            $array = explode('/',$key);
            $product = [
                'id' => $array[0],
                'title' => $array[1],
                'num' => $value
            ];
            array_push($newProductAll,$product);
        }
        return $newProductAll;
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
            ->visible(['title']);
        return [$productID_arr,$product_name,$productID_num];
    }
}
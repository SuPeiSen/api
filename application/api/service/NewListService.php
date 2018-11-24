<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 16:06
 */

namespace app\api\service;


use app\api\model\NewsAuthor;
use app\api\model\NewsList;
use Driver\Cache\Redis;

class NewListService
{
    # 获取所有产品以及分类
    public function allProducts()
    {
        $all = self::getFirstAndSecond();
        return $this->getProductArray($all);
    }
    # 一级和二级推广
    public static function getFirstAndSecond()
    {
        $model = new NewsList();
        return $model::where("category_id = '今日热门' OR category_id = '本周上线'")
            ->where('status','=',1)
            ->visible(['newsid','title','thumb','date','success_num','category_id'])
            ->select();
    }
    # 分类接口 三级推广
    public static function getPage($page_num,$page_size = 10)
    {
        $model = new NewsList();
        return $model::where("top=1 OR authorid='热门产品'")
            ->where('status','=',1)
            ->order('px desc')
            ->page($page_num,$page_size)
            ->visible(['newsid','title','thumb','date','price_min','price_max','interest','px'])
            ->select()
            ->toArray();
    }
    # 获取单个产品的数据
    public function getOneProduct($id)
    {
        $model = new NewsList();
        $result =  $model->where('newsid','=',$id)
                        ->where('status','=',1)
            ->find();
        # 先判断产品是否存在，存在则返回要显示的属性
        if(!$result){
            return $result;
        }
        $product =  $result->visible(['newsid','title','thumb','num','success_num','price_min','price_max','date',
            'interest','condition','check','introduce','px','application_url','phone_num','introduction'])
            ->toArray();
        # 同步写入redis
        $result = $this->RedisSynchronization($id,$product);
        return array($product,$result);
    }

    # 对获取的所有产品按位置进行数组分类
    private function getProductArray($products)
    {
        $first = array();
        $second = array();
        $third = array();
        foreach ($products as $key => $value){
            if($value['category_id'] == '今日热门'){
                array_push($first, $value);
            }
            if($value['category_id'] == '本周上线'){
                array_push($second, $value);
            }
            $third = self::getPage(1,8);
        }
        # 把需要重复出现的（也就是置顶的）数据插入到$third这个数组中
        return array('first' => $first, 'second' => $second, 'third' => $third);
    }
    # 根据category_id查找对应的分类产品
    public static function getProducts($category_id)
    {
        $model = new NewsList();
        return $model::where('authorid','=',$category_id)
            ->where('status','=',1)
            ->visible(['name','authorid','thumb','title','price_min','price_max',
                'date','interest','condition','status'])
            ->select()
            ->toArray();
    }
    # mysql查找产品同步写入redis
    private function RedisSynchronization($id,$products)
    {
        # 设置换成key前缀
        $result = Redis::hMset('ProductID_'.$id,$products);
        Redis::setKeyTime($id,86400);
        return $result ? 'redis success' : 'redis false';
    }
}
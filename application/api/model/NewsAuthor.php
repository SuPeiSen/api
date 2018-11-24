<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/15
 * Time: 10:23
 */

namespace app\api\model;


class NewsAuthor extends BaseModel
{
    # 关联模型，一对多关联（联表查询）
    public function products()
    {
        return $this->hasMany('NewsList','authorid','authorid')
            ->where('status','=','1');
    }
    # 根据category_id查找对应的分类
//    public static function getProducts($category_id)
//    {
//        return self::with('products')
//            ->where('authorid','=',$category_id)
//            ->visible(['name','authorid','products.thumb','products.title','products.limit',
//                'products.date','products.interest','products.condition','products.status']);
//    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 11:48
 */

namespace app\api\service;


class BannerService
{
    # 对查询轮播图数组进行分类
    public function returnData($data)
    {
        if(!$data){
            throw new MysqlErrorException([
                'code' => '500',
                'msg' => '数据库读写失败！！'
            ]) ;
        }
        $top = array();
        $category = array();
        foreach ($data as $key => $value){
            if($value['position'] == '轮播'){
                array_push($top,$value);
            }
            if($value['position'] == '精品推荐'){
                array_push($category,$value);
            }
        }
        return array('top' => $top,'category' => $category);
    }
}
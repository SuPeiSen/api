<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 10:54
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    # 封装自动获取处理url的基类函数
    protected function prefixImgUrl($value){
        $finalUrl = config('url.img_url').$value;
        return $finalUrl;
    }
}
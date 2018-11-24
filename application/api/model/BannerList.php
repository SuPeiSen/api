<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 10:54
 */

namespace app\api\model;


class BannerList extends BaseModel
{
    # 获取所有轮播图
    public static function getAllBanner()
    {
        return self::where('status','=','1')
            ->order('link','desc')
            ->all()
            ->visible(['bannerid','src','px','link','position'])
            ->toArray();
    }
    public function getSrcAttr($value)
    {
        return $this->prefixImgUrl($value);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 16:06
 */

namespace app\api\model;


class NewsList extends BaseModel
{
    public function getThumbAttr($value)
    {
        return $this->prefixImgUrl($value);
    }
}
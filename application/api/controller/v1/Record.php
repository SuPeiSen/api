<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/22
 * Time: 15:56
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use Driver\Cache\Redis;
use think\facade\Request;

class Record extends BaseController
{
    # 记录产品点击量
    public function recordProductNum()
    {
        # 拼接产品ip，使用redis的hash自增记录每个产品点击数
        $ProductsID = 'ProductID'.Request::post('id');
        $result = Redis::hincrby('OnClickNum',$ProductsID,1);
        if($result) return 'success';
        return 'false';
    }
}
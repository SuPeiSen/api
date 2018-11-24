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
        $key = Request::post('id');
        $result = Redis::incr($key);
        if($result) return 'success';
        return 'false';
    }
}
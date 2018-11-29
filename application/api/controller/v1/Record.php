<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/22
 * Time: 15:56
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\RedisService;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\MysqlErrorException;
use Driver\Cache\Redis;
use think\facade\Request;

class Record extends BaseController
{
    # 记录产品点击量
    public function recordProductNum()
    {
        # 拼接产品ip，使用redis的hash自增记录每个产品点击数
        # 把字符切割成数组 title_id
        $Products = Request::param('product');
        $result = Redis::hincrby('OnClickNum',$Products,1);
        if($result) return 'success';
        return 'false';
    }
    # 对外提供Redis里面产品点击数量api
    public function getProductsNum()
    {
        $service = new RedisService();
        $productAll = $service->getOnRedisProductNum();
        if(!$productAll){
            throw new MysqlErrorException([
               'msg' => '服务器异常',
               'code' => 404
            ]);
        }
        return json([
            'code' => 200,
            'date' => $productAll
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 16:04
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\NewsAuthor;
use app\api\model\NewsList;
use app\api\service\NewListService;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\MysqlErrorException;
use app\lib\exception\NullException;
use Driver\Cache\Redis;

class Products extends BaseController
{
    # 获取所有的产品数据
    public function getAllProducts()
    {
        $service = new NewListService();
        $allProduct = $service->allProducts();
        return json([
           'code' => 200,
           'data' => $allProduct
        ]);
    }
    # 根据id来获取单个产品数据
    public function getOneProduct($id)
    {
        # 验证客户端传过来的ID必须是正整数
        (new IDMustBePositiveInt())->goCheck();
        # 再判断redis是否有该产品，如果有则从redis读取，没有则从mysql读取并写入redis
        $redis_product = Redis::hget('product'.$id);
        if($redis_product){
            return json([
                'code' => 200,
                'data' => $redis_product,
                'redis' => 'redis hash success'
            ]);
        }
        # 从mysql查找并同步写入redis 设置redis过期时间为24小时
        $service = new NewListService();
        $oneProduct = $service->getOneProduct($id);
        if(!$oneProduct){
            throw new MysqlErrorException([
               'code' => 404,
                'msg' => '产品不存在！！'
            ]);
        }
        return json([
           'code' => 200,
            'data' => $oneProduct[0],
            'redis' => $oneProduct[1]
        ]);
    }
    public function getProductCategory($id)
    {
        # 验证客户端传过来的ID必须是正整数
        (new IDMustBePositiveInt())->goCheck();
        # 模型一对多进行关联
        $result = NewListService::getProducts($id);
        if(empty($result)){
            throw new NullException([
                'code' =>404,
                'msg' => '该分类没有数据'
            ]);
        }
        return json([
            'code' => 200,
            'data' => $result
        ]);
    }
    # 分页接口
    public function getPage($id)
    {
        # 分页参数必须是正整数
        (new IDMustBePositiveInt())->goCheck();
        $data = NewListService::getPage($id,8);
        if(empty($data)){
            throw new NullException([
               'code' => 416,
                'msg' => '没有更多！！！'
            ]);
        }
        return json([
            'code' => 200,
            'data' => $data
        ]);
    }
}
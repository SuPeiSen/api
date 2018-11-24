<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 10:49
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\BannerList;
use app\api\service\BannerService;
use app\lib\exception\MysqlErrorException;

class Banner extends BaseController
{
    # 获取所有轮播图
    public function getBanner()
    {
        $allBanner = BannerList::getAllBanner();
        $service = new BannerService();
        $array = $service->returnData($allBanner);
        return json([
            'code' => 200,
            'data' => $array
        ]);
    }
}
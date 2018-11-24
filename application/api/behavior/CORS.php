<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/15
 * Time: 15:30
 */

namespace app\api\behavior;

use think\facade\Response;
class CORS
{
    public function appInit()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET');
        if (request()->isOptions()) {
            exit();
        }
    }
}
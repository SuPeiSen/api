<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 18:03
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    // 参数错误的状态吗
    // HTTP状态码
    public $code = 409;
    // 错误具体信息
    public $msg = '参数错误';
    // 自定义错误码
    public $errorCode = 10001;
}
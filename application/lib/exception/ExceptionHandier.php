<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/13
 * Time: 16:08
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;
use think\facade\Request;
use think\Log;

class ExceptionHandier extends Handle
{

    private $code;
    private $msg;
    private $errorCode;

    #重写异常处理方法
    public function render(Exception $e)
    {
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            if(config('app_debug')){
                return parent::render($e);
            }
            $this->code = 500;
            $this->msg = '服务器内部错误';
            $this->errorCode = '999';
            $this->recorErrorLog($e);
        }

        $result = [
            'code' => $this->code,
            'error_code' => $this->errorCode,
            'request_url' => Request::url(),
            'msg' => $this->msg
        ];
        return json($result,$this->code);
    }

    # 如果不是调试模式，则写入日志
    private function recorErrorLog(\Exception $e)
    {
        Log::record($e->getMessage(),'error');
        Log::init([
            'type' => 'File',
            'level' => ['error']
        ]);
    }
}
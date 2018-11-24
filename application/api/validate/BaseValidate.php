<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/14
 * Time: 17:59
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        # 对每个参数进行验证
        $param = Request::param();
        # 调用check() 方法对每个参数进行检查
        $result = $this->batch()->check($param);
        # 如果检验失败则抛出异常
        if(!$result){
            $error = $this->error;
            throw new ParameterException($error);
        }
    }
    # 正整数验证
    protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }
    # 为空验证
    protected function isNotEmpty($value)
    {
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }
    # 手机验证
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/11/15
 * Time: 9:19
 */

namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];
}
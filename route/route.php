<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

# Banner
Route::rule('getAllBanner','api/v1.Banner/getBanner');

# Produst
Route::rule('getAllProduct','api/v1.Products/getAllProducts');
Route::get('getOneProduct/:id','api/v1.Products/getOneProduct');
Route::get('getProductCategory/:id','api/v1.Products/getProductCategory');
Route::get('getPage/:id','api/v1.Products/getPage');
    //->pattern(['page_num' => '\d+']);

Route::post('Record','api/v1.Record/recordProductNum');

<?php
/**
 * Created by caicai
 * Date 2022/7/7 0007
 * Time 10:08
 * Desc
 */
use think\facade\Route;
use app\common\ResponseJson;
Route::miss(function(\app\Request $request) {
    if ($request->isGet()) {
        return ResponseJson::status404();
    }
    return ResponseJson::fail();
});


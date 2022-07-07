<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 11:47
 * Desc
 */


namespace app\api\controller;
use app\common\controller\Api;
use app\common\ResponseJson;
use think\response\Json;
class Index extends Api
{

    public function index()
    {
        return ResponseJson::success([]);
    }

}
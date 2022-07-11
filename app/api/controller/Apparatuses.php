<?php
/**
 * Created by caicai
 * Date 2022/7/7 0007
 * Time 11:21
 * Desc
 */


namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\Apparatuses as AM;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
class Apparatuses extends Api
{
    public function lists(): J
    {
        return RJ::success(AM::lists($this->request->post()));
    }

    public function detail(): J
    {
        return RJ::success(AM::detail($this->request->post()));
    }
}
<?php
/**
 * Created by caicai
 * Date 2022/7/14 0014
 * Time 11:48
 * Desc
 */


namespace app\api\controller;

use app\common\controller\Api;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
use app\api\model\CaseSubjects as CSM;

class CaseSubjects extends Api
{
    public function map_list(): J
    {
        return RJ::success(CSM::map_list());
    }
}
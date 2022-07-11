<?php
/**
 * Created by caicai
 * Date 2022/7/8 0008
 * Time 17:10
 * Desc
 */


namespace app\api\controller;

use app\common\controller\Api;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
use app\api\model\Cases as CM;
class Cases extends Api
{
    /**
     * 病例库列表
     * @return J
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lists(): J
    {
        $data = $this->request->post();
        $params = [
            'is_top' =>  1,
            'case_type_id' =>  $data['case_type_id'] ?? [],
            'case_subject_id' =>  $data['case_subject_id'] ?? [],
            'search' =>  $data['search'] ?? '',
        ];
        return RJ::success(CM::lists($params));
    }

    public function apparatus_cases(): J
    {
        return RJ::success(CM::apparatus_cases($this->request->post()));
    }
}
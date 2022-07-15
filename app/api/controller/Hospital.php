<?php
/**
 * Created by caicai
 * Date 2022/7/15 0015
 * Time 16:32
 * Desc
 */


namespace app\api\controller;

use app\api\model\Hospitals as HM;
use app\common\controller\Api;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
use app\common\enum\UserEnumType as T;
class Hospital extends Api
{

    /**
     * 医生管理列表
     * @return J
     */
    public function doctorLists(): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::success();
        }
        return RJ::success(HM::doctorLists($this->request->post()));
    }

    /**
     * 医生详情
     * @return J
     */
    public function doctorDetail(): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::success();
        }
        if ('edit' === $this->request->post('action','')) {
            $detail = HM::doctorDetail($this->request->post());
        }else {
            $detail = HM::doctorSimpleDetail($this->request->post());
        }
        return RJ::success($detail);
    }

    /**
     * 医生详情
     * @return J
     */
    public function doctorEdit(): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::fail('无权操作');
        }
        $res = HM::doctorEdit($this->request->post());
        if (true !== $res) {
            return RJ::fail($res ?: '操作失败');
        }
        return RJ::success([], '操作成功');
    }


}
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

    protected function _dataHandle(): array
    {
        $hospital_id = ($this->request->userInfo['type'] == T::YIYUAN) ? $this->request->userInfo['hospital_id'] : ($this->request->post('hospital_id', 0));
        return array_merge($this->request->post(),['hospital_id' => $hospital_id]);
    }

    /**
     * 医生map
     * @return J
     */
    public function map_list(): J
    {
        return RJ::success(HM::map_list());
    }

    /**
     * 医生管理列表
     * @return J
     */
    public function doctorLists(): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::success();
        }
        return RJ::success(HM::doctorLists($this->_dataHandle()));
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
            $detail = HM::doctorDetail($this->_dataHandle());
        }else {
            $detail = HM::doctorSimpleDetail($this->_dataHandle());
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
        $res = HM::doctorEdit($this->_dataHandle());
        if (true !== $res) {
            return RJ::fail($res ?: '操作失败');
        }
        return RJ::success([], '操作成功');
    }

    /**
     * 医生权限操作
     * @return J
     */
    public function changeCaseAuth(): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::fail('无权操作');
        }
        $res = HM::changeCaseAuth($this->_dataHandle());
        if (true !== $res) {
            return RJ::fail($res ?: '操作失败');
        }
        return RJ::success([], '操作成功');
    }

    /**
     * 撤销医生
     * @return J
     */
    public function dockerRevoke(): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::fail('无权操作');
        }
        $res = HM::dockerRevoke($this->_dataHandle());
        if (true !== $res) {
            return RJ::fail($res ?: '操作失败');
        }
        return RJ::success([], '操作成功');
    }


}
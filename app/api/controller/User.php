<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 15:31
 * Desc
 */


namespace app\api\controller;

use app\api\model\CaseSubjects;
use app\api\model\CaseTypes;
use app\api\model\Departments;
use app\api\model\Hospitals;
use app\api\validate\HospitalValidate;
use app\api\validate\UserValidate;
use app\common\controller\Api;
use think\response\Json as J;
use app\common\ResponseJson as RJ;
use app\api\model\User as U;

class User extends Api
{
    /**
     * 获取用户信息
     * @return J
     */
    public function getUserInfo(): J
    {
        return RJ::success($this->request->userInfo);
    }

    /**
     * 更新用户微信信息， 昵称和头像
     * @return J
     */
    public function updateUserInfo(): J
    {
        $res = U::updateUserInfo($this->request->post());
        if (true !== $res) {
            return RJ::fail($res);
        }
        return RJ::success();
    }

    /**
     * 更新用户微信信息， 手机号
     * @return J
     */
    public function updateUserPhone(): J
    {
        $res = U::updateUserPhone($this->request->post());
        if (true !== $res) {
            return RJ::fail($res);
        }
        return RJ::success();
    }

    /**
     * 我的收藏
     * @return J
     */
    public function myCollections(): J
    {
        return RJ::success(U::myCollections($this->request->post()));
    }


    /**
     * 医生申请配置信息
     * @return J
     */
    public function doctorApplyConfig(): J
    {
        $configs = [
            'departments' => Departments::map_list(),
            'hospitals' => Hospitals::map_list(),
            'subjects' => CaseSubjects::map_list(),
            'types' => CaseTypes::map_list(),
        ];
        return RJ::success($configs);
    }

    /**
     * 申请医生
     * @param UserValidate $va
     * @return J
     */
    public function doctorApply(UserValidate $va): J
    {
        if ($this->request->userInfo['type'] !== 1) { // 非游客不可申请
            return RJ::fail('非法操作');
        }

        if (!$va->scene(__METHOD__)->check($this->request->post())) {
            return RJ::fail($va->getError());
        }

        $res = U::doctorApply($this->request->post());

        if (true !== $res) {
            return RJ::fail($res);
        }

        return RJ::success([], '申请成功');

    }


    public function hospitalApply(HospitalValidate $va): J
    {
        if ($this->request->userInfo['type'] !== 1) { // 非游客不可申请
            return RJ::fail('非法操作');
        }

        if (!$va->scene(__METHOD__)->check($this->request->post())) {
            return RJ::fail($va->getError());
        }

        $res = U::hospitalApply($this->request->post());

        if (true !== $res) {
            return RJ::fail($res);
        }

        return RJ::success([], '申请成功');
    }


    /**
     * 医生申请列表
     * @return J
     */
    public function doctorApplyLists(): J
    {
        if ($this->request->userInfo['type'] !== 4) { // 非医院不可查看
            return RJ::fail('您不是医院账户,非法操作');
        }
        return RJ::success(U::doctorApplyLists($this->request->post()));
    }


}
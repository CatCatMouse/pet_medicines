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
use app\common\enum\UserEnumType as T;

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
     * 加入/取消收藏
     * @return J
     */
    public function joinCollection(): J
    {
        $res = U::joinCollection($this->request->post());
        if (true !== $res) {
            return RJ::fail($res ?: '操作失败');
        }
        return RJ::success([], '操作成功');
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
        if ($this->request->userInfo['type'] !== T::YOUKE) { // 非游客不可申请
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

    /**
     * 医院合作
     * @param HospitalValidate $va
     * @return J
     */
    public function hospitalApply(HospitalValidate $va): J
    {
        if ($this->request->userInfo['type'] !== T::YOUKE) { // 非游客不可申请
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
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::fail('您无操作权限');
        }
        return RJ::success(U::doctorApplyLists($this->request->post()));
    }

    /**
     * 医生申请详情表
     * @return J
     */
    public function doctorApplyDetail(): J
    {
        return RJ::success(U::doctorApplyDetail($this->request->post()));
    }

    /**
     * 医生申请详情表
     * @return J
     */
    public function auditDoctor(): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YIYUAN])) {
            return RJ::fail('您无操作权限');
        }
        $res = U::auditDoctor($this->request->post());
        if (true !== $res) {
            return RJ::fail($res ?: '操作失败');
        }
        return RJ::success([], '操作成功');
    }


}
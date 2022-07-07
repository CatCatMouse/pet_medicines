<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 15:31
 * Desc
 */


namespace app\api\controller;

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
}
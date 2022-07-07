<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 11:10
 * Desc
 */


namespace app\api\controller;

use app\common\controller\Api;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
use app\api\model\User;

class Login extends Api
{
    public $middleware = [];

    /**
     * 登录
     * @return J
     */
    public function login(): J
    {
        $res = User::login($this->request->post());
        if (is_string($res)) {
            return RJ::fail($res);
        }
        return RJ::success($res);
    }



}
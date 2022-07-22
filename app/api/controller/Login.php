<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 11:10
 * Desc
 */


namespace app\api\controller;

use app\common\controller\Api;
use think\facade\Db;
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


    public function userAgreements()
    {
        header('Content-Type: text/html');
        $content = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>隐私协议</title>
            <style>
                .content {
                    padding: 5px 5px;
                }
        </style>
        </head>
        <body>
        <div class="content">
HTML
;
        $content .= Db::name('base_config')->where(['group_id' => 'privacy_policy', 'key_id' => 'privacy_policy'])->cache(random_int(30,180))->value('key_value', '暂无');
        $content .= '</div></body></html>';
        echo $content;
    }

}
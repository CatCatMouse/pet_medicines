<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 11:47
 * Desc
 */


namespace app\api\controller;

use app\common\controller\Api;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
use app\api\model\Apparatuses as AM;
use app\api\model\Cases as CM;
use app\common\middleware\Token;

class Index extends Api
{
    public $middleware = [
        Token::class => ['except' => 'un_login_index']
    ];

    /**
     * 首页 - 游客
     * @return J
     *
     */
    public function un_login_index(): J
    {
        return $this->index();
    }

    /**
     * 首页 - 登录客户
     * @return J
     *
     */
    public function index(): J
    {
        $lists['apparatuses'] = AM::lists(array_merge($this->request->post(), ['is_top' => 1]));
        $lists['cases'] = CM::lists(array_merge($this->request->post(), ['is_top' => 1]));
        return RJ::success($lists);
    }

}
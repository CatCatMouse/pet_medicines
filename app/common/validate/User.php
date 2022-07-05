<?php
/**
 *
 * User.php
 * DateTime: 2022-07-05 09:33:02
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 用户表 验证器
 * Class User
 * @package app\common\validate\User
 */
class User extends BaseValidate
{
    protected $rule = [
        'id|用户表' => 'require|number',
        'pid|上级' => 'require|number',
        'type|权限类型' => 'require|number|in:1,2,3,4',
        'nickname|昵称' => 'require',
        'name|姓名' => 'require',
        'phone|手机号' => 'require|mobile',
        'password|密码' => 'require',
        'avatar|头像' => 'require',
        'account|账号' => 'require',
        'id_card|身份证号' => 'require',
        'wx_openid|微信openid' => 'require',
        'invite_code|邀请码' => 'require',
        'zfb_user_id|支付宝user_id' => 'require',
        'vip_level|会员等级' => 'require|number',
        'balance|余额，分' => 'require|number',
        'integral|积分' => 'require|number',
        'email|邮箱' => 'require',
        'status|状态' => 'require|number|in:1,2,3',
    ];

    protected $scene = [
        'create' => ['pid', 'type', 'nickname', 'name', 'phone', 'password', 'avatar', 'account', 'id_card', 'wx_openid', 'invite_code', 'zfb_user_id', 'vip_level', 'balance', 'integral', 'email', 'status'],
        'update' => ['id', 'pid', 'type', 'nickname', 'name', 'phone', 'password', 'avatar', 'account', 'id_card', 'wx_openid', 'invite_code', 'zfb_user_id', 'vip_level', 'balance', 'integral', 'email', 'status'],
    ];
}

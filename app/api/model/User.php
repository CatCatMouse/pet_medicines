<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 14:40
 * Desc
 */


namespace app\api\model;

use sdModule\common\Sc;
use think\facade\Db;
use weChat\appLet\Login as appLetLogin;

class User
{
    public static $table_name = 'user';
    protected static $withoutField = [
        'pid',
        'password',
        'create_time',
        'update_time',
        'delete_time',
    ];

    /**
     * 用户信息
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function userInfo(int $id): array
    {
        if (empty($id)) {
            return [];
        }
        $userInfo = Db::name(static::$table_name)->where('id', $id)->withoutField(static::$withoutField)->cache(random_int(10, 25))->find() ?? [];
        return (array)$userInfo;
    }

    /**
     * 生成token
     * @param array $data
     * @return array
     */
    public static function getToken(array $params): array
    {
        return Sc::jwt($params)->setExp(24 * 3600 * 7)->getRefresh()->getToken();
    }

    public static function login(array $params)
    {
        if (empty($params['code'])) {
            return '请授权登录';
        }

        $openId = (new appLetLogin())->getUserOpenid($params['code']);
        if (empty($openId)) {
            return '参数错误';
        }

        $user = Db::name(static::$table_name)->where('wx_openid', $openId)->find();

        if (empty($user)) {
            $uid = Db::name(static::$table_name)->insertGetId([
                'nickname' => $params['nickname'] ?? '',
                'name' => $params['name'] ?? '',
                'phone' => $params['phone'] ?? '',
                'avatar' => $params['avatar'] ?? '',
                'wx_openid' => $openId,
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ]);
            if (!$uid) {
                return '系统繁忙';
            }
        } else {
            if (1 !== $user['status']) {
                return '账户异常';
            }
            $uid = $user['id'];
        }
        return array_merge(static::getToken(['id' => $uid]), ['userinfo' => static::userInfo($uid)]);
    }

    public static function updateUserInfo(array $params)
    {

        $userInfo = (new appLetLogin())->getUserinfo(
            $params['code'] ?? ''
            , $params['rawData'] ?? ''
            , $params['signature'] ?? ''
            , $params['iv'] ?? ''
            , $params['encryptedData'] ?? ''
        );
        if (empty($userInfo)) {
            return '参数错误';
        }

        /**
         *  正确解析返回的数据格式
         * {
         * "openId": "OPENID",
         * "nickName": "NICKNAME",
         * "gender": GENDER,
         * "city": "CITY",
         * "province": "PROVINCE",
         * "country": "COUNTRY",
         * "avatarUrl": "AVATARURL",
         * "unionId": "UNIONID",
         * "watermark": {
         * "appid":"APPID",
         * "timestamp":TIMESTAMP
         * }
         * }
         */

        $user = Db::name(static::$table_name)->where('wx_openid', $userInfo['openId'])->find();
        if (empty($user)) {
            return '请先授权登录';
        }

        if (!Db::name(static::$table_name)->where('id', $user['id'])->update([
            'nickName' => $userInfo['nickName'],
            'avatar' => $userInfo['avatar'],
            'update_time' => date('Y-m-d H:i:s'),
        ])) {
            return '系统繁忙';
        }
        return true;
    }

    public static function updateUserPhone(array $params)
    {
        $userPhone = (new appLetLogin())->getPhone($params['code'] ?? '');
        if (empty($userPhone)) {
            return '参数错误';
        }

        /**
         *  正确解析返回的数据格式
         * {
         * "errcode":0,
         * "errmsg":"ok",
         * "phone_info": {
         * "phoneNumber":"xxxxxx",
         * "purePhoneNumber": "xxxxxx",
         * "countryCode": 86,
         * "watermark": {
         * "timestamp": 1637744274,
         * "appid": "xxxx"
         * }
         * }
         * }
         */
        $uid = request()->userInfo['id'];
        if (!Db::name(static::$table_name)->where('id', $uid)->update([
            'phone' => $userPhone['phone_info']['phoneNumber'],
            'update_time' => date('Y-m-d H:i:s'),
        ])) {
            return '系统繁忙';
        }
        return true;
    }
}
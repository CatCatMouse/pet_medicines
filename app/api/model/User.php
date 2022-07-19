<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 14:40
 * Desc
 */


namespace app\api\model;

use sdModule\common\Sc;
use think\Exception;
use think\facade\Cache;
use think\facade\Db;
use weChat\appLet\Login as appLetLogin;
use app\common\enum\UserEnumType as T;
use app\common\enum\HospitalsEnumStatus as HE;
use app\api\model\Hospitals as HM;
use app\api\model\Cases as CM;

class User
{
    public static $table_name = 'user';
    public static $table_doctor_application_name = 'user_doctor_applications';
    public static $table_hospital_name = 'hospitals';
    public static $table_collection_name = 'case_collections';
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
        $cache_key = md5('user_info_' . $id);
        if (1 > 2 && Cache::has($cache_key)) {
            $userInfo = json_decode(Cache::get($cache_key), true) ?? [];
        } else {
            $userInfo = Db::name(static::$table_name)->where('id', $id)->withoutField(static::$withoutField)->find() ?? [];
            if (!empty($userInfo)) {
                switch ($userInfo['type']) {
                    case T::YOUKE: // 游客
                        break;
                    case T::XIAOSHOU: //销售 - 归属公司信息和管理医院信息
                        $userInfo['hospital_info'] = static::bindHospitalLists(['user_id' => $userInfo['id']]);
                        $userInfo['factory_info'] = Db::name('factories')->where('id', $userInfo['factory_id'])->find() ?? [];
                        break;
                    case T::YISHENG: // 医生 - 归属医院信息
                        $userInfo['hospital_info'] = Db::name('user_doctor_applications')->where('user_id', $userInfo['id'])->where(['status' => 2])->find() ?? [];
                        break;
                    case T::YIYUAN: // 医院 - 医院信息
                        $userInfo['hospital_info'] = Db::name('hospitals')->where('id', $userInfo['hospital_id'])->find() ?? [];
                        break;
                    default: // 其他
                        break;
                }
            }
            Cache::set($cache_key, json_encode($userInfo), random_int(15, 60));
        }
        return $userInfo;
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

    /**
     * 登录
     * @param array $params
     * @return array|array[]|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function login(array $params)
    {
        if (empty($params['code'])) {
            return '请授权登录';
        }
        $dev = intval($params['dev'] ?? 0);
        if (!!env('app_debug', false) && 1 === $dev) {
            $openId = $params['openId'];
        } else {
            $openId = (new appLetLogin())->getUserOpenid($params['code']);
            if (empty($openId)) {
                return '参数错误';
            }
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

    /**
     * 更新用户微信昵称和头像
     * @param array $params
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function updateUserInfo(array $params)
    {
        if (false) {
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

            $nickname = $userInfo['nickName'];
            $avatar = $userInfo['avatar'];
        } else {
            $nickname = $params['nickname'] ?? '';
            $avatar = $params['avatar'] ?? '';
        }


        if (!Db::name(static::$table_name)->where('id', request()->userInfo['id'])->update([
            'nickname' => $nickname,
            'avatar' => $avatar,
            'update_time' => date('Y-m-d H:i:s'),
        ])) {
            return '系统繁忙';
        }
        return true;
    }

    /**
     * 更新用户微信绑定的手机号
     * @param array $params
     * @return bool|string
     * @throws \think\db\exception\DbException
     */
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

    /**
     * 我的收藏
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function myCollections(array $params): array
    {
        $where = [];
        switch (request()->userInfo['type']) {
            case 1:
                $where = [
                    ['c.is_top', '=', 1],
                ];
                break;
            default:
                break;
        }
        $field = "cc.id as collection_id,c.id,c.name,c.create_time,c.desc,c.imgs,1 as if_collection";
        $order = 'cc.id desc';
        $lists = Db::name(static::$table_collection_name)->alias('cc')
            ->join('cases c', 'cc.case_id = c.id')
            ->where($where)
            ->page(intval($params['page'] ?? 1))
            ->limit(intval($params['limit'] ?? 20))
            ->fieldRaw($field)
            ->orderRaw($order)
            ->select()
            ->toArray();
        foreach ($lists as &$v) {
            $v['imgs'] = explode(',', $v['imgs']);
        }
        return $lists;
    }

    /**
     * 加入/取消收藏
     * @param array $params
     * @return bool|string
     */
    public static function joinCollection(array $params)
    {
        $uid = request()->userInfo['id'];
        $case_id = intval($params['case_id'] ?? 0);
        $date = date('Y-m-d H:i:s');
        if (empty($case_id)) {
            return false;
        }
        $check = Db::name(static::$table_collection_name)->where([
            'user_id' => $uid,
            'case_id' => $case_id
        ])->find();

        if (empty($check)) {
            $insert = [
                'user_id' => $uid,
                'case_id' => $case_id,
                'create_time' => $date,
            ];
        }

        if (!empty($insert)) {
            if (!Db::name(static::$table_collection_name)->insert($insert)) {
                return '系统繁忙';
            }
        } else {
            if (!Db::name(static::$table_collection_name)->where('id', $check['id'])->delete(true)) {
                return '系统繁忙';
            }
        }
        return true;
    }

    /**
     * 申请医生
     * @param array $params
     * @return bool|string
     */
    public static function doctorApply(array $params)
    {
        try {
            Db::startTrans();
            $user_id = request()->userInfo['id'];

            $check_hospital = Db::name(static::$table_hospital_name)->where([
                ['operate_id', '=', $user_id],
                ['status', '>', 0],
            ])->find();
            if (!empty($check_hospital)) {
                throw new Exception('您已提交过医院合作申请了');
            }
            $check = Db::name(static::$table_doctor_application_name)->where([
                ['user_id', '=', $user_id],
                ['status', '>', 0]
            ])->find();
            if (!empty($check)) {
                throw new Exception('请勿重复提交');
            }
            $date = date('Y-m-d H:i:s');
            $insert = [
                'user_id' => $user_id,
                'user_name' => $params['user_name'],
                'hospital_id' => $params['hospital_id'],
                'department_id' => $params['department_id'],
                'good_subjects' => $params['good_subjects'] ?? '',
                'case_type_ids' => json_encode($params['case_type_ids'] ?? []),
                'case_subject_ids' => json_encode($params['case_subject_ids'] ?? []),
                'create_time' => $date,
            ];

            //垃圾数据清理
            Db::name(static::$table_doctor_application_name)->where([
                'user_id' => $user_id,
                'status' => -1,
            ])->delete(true);
            Db::name(static::$table_hospital_name)->where([
                'operate_id' => $user_id,
                'status' => -1,
            ])->delete(true);

            if (!Db::name(static::$table_doctor_application_name)->insert($insert)) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }

    /**
     * 新增医院合作
     * @param array $params
     * @return bool|string
     */
    public static function hospitalAdd(array $params)
    {
        try {
            Db::startTrans();
            $user_id = request()->userInfo['id'];
            $where = [
                ['name', '=', $params['name']],
                ['status', '>', 0]
            ];
            $check_hospital = Db::name(static::$table_hospital_name)->where($where)->find();
            if (!empty($check_hospital)) {
                throw new Exception('请勿重复提交');
            }

            $date = date('Y-m-d H:i:s');
            $insert = [
                'operate_id' => $user_id,
                'name' => $params['name'],
                'contact_name' => $params['contact_name'],
                'contact_phone' => $params['contact_phone'],
                'lng' => $params['lng'],
                'lat' => $params['lat'],
                'address' => $params['address'],
                'desc' => $params['desc'],
                'create_time' => $date,
            ];

            if (!Db::name(static::$table_hospital_name)->insert($insert)) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            if (preg_match('/Duplicate entry/', $e->getMessage())) {
                $msg = '医院名称已存在';
            }
            return $msg ?? $e->getMessage();
        }
        return true;
    }

    /**
     * 申请医院合作
     * @param array $params
     * @return bool|string
     */
    public static function hospitalApply(array $params)
    {
        try {
            Db::startTrans();
            $user_id = request()->userInfo['id'];
            $where = [
                ['operate_id', '=', $user_id],
                ['status', '>', 0]
            ];
            $check_hospital = Db::name(static::$table_hospital_name)->where($where)->find();
            if (!empty($check_hospital)) {
                throw new Exception('请勿重复提交');
            }
            $check = Db::name(static::$table_doctor_application_name)->where([
                ['user_id', '=', $user_id],
                ['status', '>', 0]
            ])->find();

            if (!empty($check)) {
                throw new Exception('您已经提交过医生申请了');
            }

            $date = date('Y-m-d H:i:s');
            $insert = [
                'operate_id' => $user_id,
                'name' => $params['name'],
                'contact_name' => $params['contact_name'],
                'contact_phone' => $params['contact_phone'],
                'lng' => $params['lng'],
                'lat' => $params['lat'],
                'address' => $params['address'],
                'desc' => $params['desc'],
                'create_time' => $date,
            ];

            //垃圾数据清理
            Db::name(static::$table_doctor_application_name)->where([
                'user_id' => $user_id,
                'status' => -1,
            ])->delete(true);
            Db::name(static::$table_hospital_name)->where([
                'operate_id' => $user_id,
                'status' => -1,
            ])->delete(true);

            if (!Db::name(static::$table_hospital_name)->insert($insert)) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            if (preg_match('/Duplicate entry/', $e->getMessage())) {
                $msg = '医院名称已存在';
            }
            return $msg ?? $e->getMessage();
        }
        return true;
    }

    /**
     * 医生申请列表
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function doctorApplyLists(array $params): array
    {
        $where = [
            ['a.hospital_id', '=', $params['hospital_id']],
            ['a.status', '=', 1],
            ['u.status', '=', 1],
        ];
        $field = 'a.id,a.user_id,a.user_name,d.name as department_name,a.create_time';
        $order = 'a.id desc';
        $lists = Db::name(static::$table_doctor_application_name)->alias('a')
            ->join('departments d', 'a.department_id = d.id')
            ->join((static::$table_name) . ' u', 'a.user_id = u.id')
            ->where($where)
            ->field($field)
            ->order($order)
            ->page(intval($params['page'] ?? 1))
            ->limit(intval($params['limit'] ?? 20))
            ->select()
            ->toArray();
        return $lists;
    }

    /** 医生申请详情 */
    public static function doctorApplyDetail(array $params): array
    {
        $where = [
            ['a.id', '=', intval($params['id'] ?? 0)],
        ];

        switch (request()->userInfo['type']) {
            case T::YOUKE:
                $where[] = ['a.user_id', '=', request()->userInfo['id']];
                break;
            case T::YISHENG:
                return [];
            case T::XIAOSHOU:
            case T::YIYUAN:
                $where[] = ['a.hospital_id', '=', $params['hospital_id']];
                break;
            default:
                return [];
                break;
        }

        $detail = Db::name(static::$table_doctor_application_name)->alias('a')
                ->where($where)
                ->find() ?? [];
        if (!empty($detail)) {
            $detail['case_type_ids'] = json_decode($detail['case_type_ids'], true) ?: [];
            $detail['case_subject_ids'] = json_decode($detail['case_subject_ids'], true) ?: [];
        }
        return $detail;
    }

    /**
     * 医生审核
     * @param array $params
     * @return bool|string
     */
    public static function auditDoctor(array $params)
    {
        try {
            Db::startTrans();
            $status = $params['status'] ?? -1;
            if (!in_array($status, [-1, 2])) {
                throw new Exception('非法操作');
            }
            $where = [
                'id' => intval($params['id'] ?? 0),
                'hospital_id' => $params['hospital_id'],
                'status' => 1,
            ];
            $check = Db::name(static::$table_doctor_application_name)->where($where)->find();
            if (empty($check)) {
                throw new Exception('请勿重复操作');
            }
            $date = date('Y-m-d H:i:s');
            $update = [
                'status' => $status,
                'note' => $params['note'] ?? '',
                'audit_id' => request()->userInfo['id'],
                'update_time' => $date,
            ];

            if (!Db::name(static::$table_doctor_application_name)->where('id', $check['id'])->where($where)->update($update)) {
                throw new Exception('系统繁忙');
            }
            //通过更新用户状态
            if (2 === $status && !Db::name(static::$table_name)->where(['id' => $check['user_id'], 'type' => T::YOUKE])
                    ->update(['type' => T::YISHENG, 'name' => $check['user_name'], 'update_time' => $date])) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }

        return true;
    }

    /** 销售负责的医院列表 */
    public static function bindHospitalLists(array $params): array
    {
        $where = [
            's.user_id' => request()->userInfo['id'] ?? ($params['user_id'] ?? 0),
        ];
        if (!empty($params['status']) && in_array($params['status'], [HE::SHENHETONGGUO, HE::SHENHESHIBAI])) {
            $where['h.status'] = $params['status'];
        }

        $order = 's.id desc';
        $field = 'h.id as hospital_id,h.name,h.status,h.address,h.contact_name,h.contact_phone';
        $lists = Db::name('sale_hospitals')->alias('s')
            ->join((static::$table_hospital_name) . ' h', 's.hospital_id = h.id')
            ->where($where)
            ->page($params['page'] ?? 1)
            ->limit($params['limit'] ?? 20)
            ->order($order)
            ->field($field)
            ->select()
            ->toArray();
        return $lists;
    }

    /** 销售绑定的医院详情 */
    public static function bindHospitalDetail(array $params): array
    {
        $hospital_id = $params['hospital_id'];
        $detail = HM::hospitalDetail(['hospital_id' => $hospital_id]);
        if (!empty($detail)) {
            $detail['doctors'] = HM::doctorLists(['hospital_id' => $hospital_id]);
            $detail['cases'] = CM::lists(['hospital_id' => $hospital_id]);
        }
        return $detail;
    }
}
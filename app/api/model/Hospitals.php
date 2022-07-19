<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 11:56
 * Desc
 */


namespace app\api\model;

use app\common\enum\UserEnumType as T;
use think\Exception;
use think\facade\Db;

class Hospitals
{
    public static $table_name = 'hospitals';
    public static $table_doctor_application_name = 'user_doctor_applications';

    public static function map_list(): array
    {
        try {

            $where = [
                ['h.status', '=', 2]
            ];
            switch (request()->userInfo['type']) {
                case T::XIAOSHOU:
                    return User::bindHospitalLists(['limit' => 99]);
                case T::YISHENG:
                case T::YIYUAN:
                    $where[] = ['h.id', '=', request()->userInfo['hospital']];
                    $where = array_reverse($where);
                    return Db::name(static::$table_name)->alias('h')->where($where)->field('id,name')->cache(random_int(30, 180))->select()->toArray();
                case T::YOUKE:
                default:
                    return [];
            }
        } catch (Exception $e) {
            return [];
        }
    }

    /** 医院详情表 */
    public static function hospitalDetail(array $params): array
    {
        $where = [
            ['h.id', '=', $params['hospital_id']],
            ['h.status', '<>', 1],
        ];
        $field = 'h.id,h.name,h.status,h.address,h.desc,h.contact_name,h.contact_phone';

        return Db::name(static::$table_name)->alias('h')->where($where)->field($field)->find() ?? [];
    }

    public static function doctorLists(array $params): array
    {
        $where = [
            ['a.hospital_id', '=', $params['hospital_id']],
            ['a.status', '=', 2],
            ['u.status', '=', 1],
        ];
        if (!empty($params['search'])) {
            $where[] = ['u.name', 'like', "%{$params['search']}%"];
        }

        $field = 'a.id,a.user_id,a.user_name,d.name as department_name,a.create_time';
        $order = 'a.id desc';
        $lists = Db::name(static::$table_doctor_application_name)->alias('a')
            ->join('departments d', 'a.department_id = d.id')
            ->join('user u', 'a.user_id = u.id')
            ->where($where)
            ->field($field)
            ->order($order)
            ->page(intval($params['page'] ?? 1))
            ->limit(intval($params['limit'] ?? 20))
            ->select()
            ->toArray();
        return $lists;
    }

    public static function doctorSimpleDetail(array $params): array
    {
        $where = [
            ['a.id', '=', intval($params['id'] ?? 0)],
            ['a.status', '=', 2],
        ];

        switch (request()->userInfo['type']) {
            case T::XIAOSHOU:
            case T::YIYUAN:
                $where[] = ['a.hospital_id', '=', $params['hospital_id']];
                break;
            case T::YOUKE:
            case T::YISHENG:
            default:
                return [];
        }
        $field = 'a.id,a.user_id,u.name,u.create_time,u.avatar, u.phone,a.case_auth,a.case_type_ids,a.case_subject_ids
            ,(select name from sd_hospitals where id = u.hospital_id) as hospital_name
            ,(select name from sd_departments where id = a.department_id limit 1) as department_name
            ,a.good_subjects
        ';
        $detail = Db::name(static::$table_doctor_application_name)->alias('a')
                ->join('user u', 'a.user_id = u.id')
                ->where($where)
                ->field($field)
                ->find() ?? [];
        if (!empty($detail)) {
            $detail['case_type_ids'] = json_decode($detail['case_type_ids'], true) ?: [];
            $detail['case_subject_ids'] = json_decode($detail['case_subject_ids'], true) ?: [];
            $case_types = Db::name('case_types')->where('id', 'in', $detail['case_type_ids'])->field('name')->select()->toArray();
            $case_subjects = Db::name('case_subjects')->where('id', 'in', $detail['case_subject_ids'])->field('name')->select()->toArray();
            $detail['care_contents'] = implode('丶', array_column(array_merge($case_types, $case_subjects), 'name'));
        }
        return $detail;
    }

    public static function doctorDetail(array $params): array
    {
        $where = [
            ['a.id', '=', intval($params['id'] ?? 0)],
            ['a.status', '=', 2],
        ];

        switch (request()->userInfo['type']) {
            case T::XIAOSHOU:
            case T::YIYUAN:
                $where[] = ['a.hospital_id', '=', $params['hospital_id']];
                break;
            case T::YOUKE:
            case T::YISHENG:
            default:
                return [];
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
     * 医生编辑
     * @param array $params
     * @return bool|string
     */
    public static function doctorEdit(array $params)
    {
        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $update = [
                'user_name' => $params['user_name'] ?? '',
                'department_id' => intval($params['department_id'] ?? 0),
                'good_subjects' => $params['good_subjects'] ?? '',
                'case_type_ids' => json_encode($params['case_type_ids'] ?? []),
                'case_subject_ids' => json_encode($params['case_subject_ids'] ?? []),
                'update_time' => $date,
            ];
            $where = [
                'id' => intval($params['id'] ?? 0),
                'hospital_id' => $params['hospital_id'],
                'status' => 2,
            ];
            $check = Db::name(static::$table_doctor_application_name)->where($where)->find();
            if (empty($check)) {
                throw new Exception('错误操作');
            }
            if (!Db::name(static::$table_doctor_application_name)->where($where)->update($update)) {
                throw new Exception('系统繁忙');
            }
            if (!Db::name('user')->where('id', $check['user_id'])->update([
                'name' => $update['user_name'],
                'update_time' => $date,
            ])) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }

    /** 医生的病例操作权限控制 */
    public static function changeCaseAuth(array $params)
    {
        try {
            Db::startTrans();
            $where = [
                'id' => intval($params['id'] ?? 0),
                'user_id' => intval($params['user_id'] ?? 0),
                'hospital_id' => $params['hospital_id'],
                'status' => 2,
            ];

            $check = Db::name(static::$table_doctor_application_name)->where($where)->find();
            if (empty($check)) {
                throw new Exception('未知医生');
            }

            $update = [
                'case_auth' => ($check['case_auth'] == 1) ? -1 : 1,
                'update_time' => date('Y-m-d H:i:s')
            ];
            if (!Db::name(static::$table_doctor_application_name)->where($where)->update($update)) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }

    /** 撤销医生-> 变为游客 */
    public static function dockerRevoke(array $params)
    {
        try {
            Db::startTrans();

            $where = [
                'id' => intval($params['id'] ?? 0),
                'user_id' => intval($params['user_id'] ?? 0),
                'hospital_id' => $params['hospital_id'],
                'status' => 2,
            ];

            $check = Db::name(static::$table_doctor_application_name)->where($where)->find();
            if (empty($check)) {
                throw new Exception('请勿重复操作');
            }

            //删除该用户医生信息
            if (!Db::name(static::$table_doctor_application_name)->where($where)->delete(true)) {
                throw new Exception('系统繁忙1');
            }
            //修改用户为游客状态
            if (!Db::name('user')->where(['id' => $check['user_id'], 'type' => T::YISHENG, 'hospital_id' => $check['hospital_id']])
                ->update(['type' => T::YOUKE, 'hospital_id' => 0, 'update_time' => date('Y-m-d H:i:s')])
            ) {
                throw new Exception('系统繁忙2');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }
}
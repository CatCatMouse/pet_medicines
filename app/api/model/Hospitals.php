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
        $where = [
            ['status', '=', 2]
        ];
        return Db::name(static::$table_name)->where($where)->field('id,name')->cache(random_int(30, 180))->select()->toArray();
    }

    public static function doctorLists(array $params): array
    {
        $where = [
            ['a.hospital_id', '=', intval(request()->userInfo['hospital_id'] ?? 0)],
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
                $where[] = ['a.hospital_id', '=', request()->userInfo['hospital_id']];
                break;
            case T::YOUKE:
            case T::YISHENG:
            default:
                return [];
        }
        $field = 'a.id,u.name,u.create_time,u.avatar, u.phone,a.case_auth,a.case_type_ids,a.case_subject_ids
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
                $where[] = ['a.hospital_id', '=', request()->userInfo['hospital_id']];
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
                'hospital_id' => request()->userInfo['hospital_id'],
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
}
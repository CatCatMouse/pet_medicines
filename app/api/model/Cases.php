<?php
/**
 * Created by caicai
 * Date 2022/7/7 0007
 * Time 11:25
 * Desc
 */


namespace app\api\model;

use app\common\enum\CasesIsTop as C_IsTop;
use think\facade\Db;
use app\common\enum\UserEnumType as T;
class Cases
{
    public static $table_name = 'cases';
    public static $table_apparatus_name = 'case_operation_apparatus_logs';

    /**
     * 病例列表
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function lists(array $params): array
    {
        $where = [
            ['c.delete_time', '=', 0]
        ];

        $uid = request()->userInfo['id'] ?? 0;
        /** 置顶 */
        if (in_array($params['is_top'] ?? -1, C_IsTop::getAll())) {
            $where[] = ['c.is_top', '=', $params['is_top']];
        }
        /** 医院id */
        if (!empty($params['hospital_id'])) {
            $where[] = ['c.hospital_id', '=', $params['hospital_id']];
        }
        /** 分类 */
        if (!empty($params['case_type_id']) && is_array($params['case_type_id']) ) {
            if (count($params['case_type_id']) == 1) {
                $where[] = ['c.case_type_id', '=', $params['case_type_id'][0]];
            } else {
                $where[] = ['c.case_type_id', 'in', $params['case_type_id']];
            }
        }
        /** 科目 */
        if (!empty($params['case_subject_id']) && is_array($params['case_subject_id'])) {
            if (count($params['case_subject_id']) == 1) {
                $where[] = ['c.case_subject_id', '=', $params['case_subject_id'][0]];
            } else {
                $where[] = ['c.case_subject_id', 'in', $params['case_subject_id']];
            }
        }
        /** 品种 */
        if (!empty($params['variety_id'])) {
            $where[] = ['c.variety_id', '=', $params['variety_id']];
        }
        /** 医生id */
        if (!empty($params['operate_id'])) {
            $where[] = ['c.operate_id', '=', $params['operate_id']];
        }

        /** 销售人id */
        if (!empty($params['sale_id'])) {
            $where[] = ['c.sale_id', '=', $params['sale_id']];
        }

        if (!empty($params['search'])) {
            $where[] = ['c.name', 'like', "%{$params['search']}%"];
        }

        $field = "c.id,c.name,c.create_time,c.desc,c.imgs,if(cc.user_id = {$uid},true,false) as if_collection";
        $order = 'c.id desc';
        $lists = Db::name(static::$table_name)->alias('c')
            ->join('case_collections cc', 'c.id = cc.case_id and cc.user_id = ' . $uid, 'left')
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
     * 使用器械的关联病例
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function apparatus_cases(array $params): array
    {
        $apparatus_id = intval($params['apparatus_id'] ?? 0);
        if (empty($apparatus_id)) {
            return [];
        }
        $where = [
            'ac.apparatus_id' => $apparatus_id
        ];

        $uid = request()->userInfo['id'] ?? 0;

        if (in_array($params['is_top'] ?? -1, C_IsTop::getAll())) {
            $where['c.is_top'] = $params['is_top'];
        }

        $field = "c.id,c.name,c.create_time,c.desc,c.imgs, if(cc.user_id = {$uid},true,false) as if_collection";
        $order = 'c.id desc';
        $lists = Db::name(static::$table_apparatus_name)->alias('ac')
            ->join((static::$table_name) . ' c', 'ac.case_id = c.id')
            ->join('case_collections cc', 'c.id = cc.case_id and cc.user_id = ' . $uid, 'left')
            ->where($where)
            ->page(intval($params['page'] ?? 1))
            ->limit(intval($params['limit'] ?? 20))
            ->fieldRaw($field)
            ->orderRaw($order)
            ->group('c.id')
            ->select()
            ->toArray();
        foreach ($lists as &$v) {
            $v['imgs'] = explode(',', $v['imgs']);
        }
        return $lists;
    }

    public static function detail(array $params)
    {
        $uid = request()->userInfo['id'];
        $where = [
            ['c.id', '=', intval($params['id'] ?? 0)]
        ];
        /** 游客只能看推荐的 */
        if(T::YOUKE === request()->userInfo['type']) {
            $where[] = ['c.is_top', '=', 1];
        }
        $field = "
            c.id,c.name,c.create_time,c.desc,c.imgs,c.videos
            ,if(cc.user_id = {$uid},true,false) as if_collection
            ,ifnull(ct.name, '') as type_name
            ,ifnull(cs.name, '') as subject_name
            ,ifnull(v.name, '') as variety_name
            ,concat(c.age_year,'岁',if(c.age_month = 0,'',concat(c.age_month,'个月'))) as age
            ,if(c.sex=1,'公',if(c.sex=2,'母','未知')) as sex
            ,c.desc
        ";

        $detail = Db::name(static::$table_name)->alias('c')
                ->join('case_collections cc', 'c.id = cc.case_id and cc.user_id = ' . $uid, 'left')
                ->join('case_types ct', 'c.case_type_id = ct.id', 'left')
                ->join('case_subjects cs', 'c.case_subject_id = cs.id', 'left')
                ->join('varieties v', 'c.variety_id = v.id', 'left')
                ->where($where)
                ->field($field)
                ->find() ?? [];

        if (!empty($detail)) {
            $detail['imgs'] = array_filter(explode(',', $detail['imgs']));
            $detail['videos'] = array_filter(explode(',', $detail['videos'] ??''));
            $detail['operation_lists'] = CaseOperations::case_operations($detail['id']);
        }

        return $detail;
    }
}
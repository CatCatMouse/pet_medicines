<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 17:19
 * Desc
 */


namespace app\api\model;

use think\facade\Db;

class CaseOperations
{
    public static $table_name = 'case_operations';

    public static function case_operations(int $case_id): array
    {
        $where = [
            ['co.case_id', '=', $case_id],

        ];
        $field = '
        co.id,co.case_id,co.name,co.start_time
        ,(select group_concat(concat(apparatus_id,":",apparatus_name)) from sd_case_operation_apparatus_logs where operation_id = co.id limit 1) as apparatus
        ';
        $order = 'co.id desc';
        $lists = Db::name(static::$table_name)->alias('co')
            ->where($where)
            ->fieldRaw($field)
            ->order($order)
            ->select()

            ->toArray();
        foreach ($lists as &$v) {
            if (empty($v['apparatus'])) {
                $v['apparatus'] = [];
                continue;
            }
            $apparatus = explode(',', $v['apparatus']);
            $v['apparatus'] = [];
            foreach ($apparatus as $v2) {
                $t = explode(':', $v2);
                $v['apparatus'][$t[0]] = [
                    'id' => $t[0],
                    'name' => $t[1],
                ];
            }
        }
        return ['lists' => $lists, 'total' => count($lists)];
    }
}
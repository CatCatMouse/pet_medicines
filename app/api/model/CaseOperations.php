<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 17:19
 * Desc
 */


namespace app\api\model;

use think\Exception;
use think\facade\Cache;
use think\facade\Db;

class CaseOperations
{
    public static $table_name = 'case_operations';
    public static $table_detail_name = 'case_operation_details';
    public static $table_operation_apparatus_name = 'case_operation_apparatus_logs';

    /** 病例手术信息 */
    public static function case_operations(int $case_id): array
    {
        $where = [
            ['co.case_id', '=', $case_id],

        ];
        $field = '
        co.id,co.case_id,co.name,co.start_time
        ,(select group_concat(concat(apparatus_id,":",apparatus_name,":",apparatus_type)) from sd_case_operation_apparatus_logs where operation_id = co.id limit 1) as apparatus
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
                    'type' => $t[2],
                ];
            }
        }
        return ['lists' => $lists, 'total' => count($lists)];
    }

    public static function detail(array $params): array
    {
        $key = 'api-case_operation-detail' . md5(json_encode($params));
        if (Cache::has($key)) {
            $detail = Cache::get($key);
        } else {
            $where = [
                'co.id' => $params['id'] ?? 0,
                'co.case_id' => $params['case_id'] ?? 0,
            ];
            $field = 'co.*, ifnull(h.name,"") as hospital_name';
            $detail = Db::name(static::$table_name)->alias('co')->join('hospitals h', 'co.hospital_id = h.id', 'left')->where($where)->field($field)->find();
            if (empty($detail)) {
                return [];
            }

            $operation_field = '
                    d.id,d.case_id,d.hospital_id,d.operation_id,d.type,d.start_time,d.info,d.videos,d.imgs
                    ,(if(d.type=2,((select group_concat(concat(apparatus_id,"@@@",apparatus_name,"@@@",apparatus_type) separator "***") from sd_case_operation_apparatus_logs where operation_detail_id = d.id)),"")) as operation_apparatuses
        ';
            $operation_details = Db::name(static::$table_detail_name)->alias('d')->where('d.operation_id', $detail['id'])->field($operation_field)->order('d.id desc')->select()->toArray();

            foreach ($operation_details as &$v) {
                $v['imgs'] = array_filter(explode(',', $v['imgs']));
                $v['videos'] = array_filter(explode(',', $v['videos'] ?? ''));
                $operation_apparatuses = $v['operation_apparatuses'];
                $v['operation_apparatuses'] = [];
                foreach (array_filter(explode('***', $operation_apparatuses)) as $v1) {
                    $operation_apparatus = array_filter(explode('@@@', $v1));
                    $v['operation_apparatuses'][] = [
                        'id' => (($operation_apparatus[2] ?? 2) == 2)?0:($operation_apparatus[0]??0),
                        'name' => $operation_apparatus[1] ?? '',
                    ];
                }
            }
            $detail['details'] = $operation_details;
            Cache::set($key, $detail, random_int(15, 45));
        }
        return $detail;

    }

    public static function operation_type_detail(array $params): array
    {
        $where = [
            'id' => $params['id'] ?? 0,
            'case_id' => $params['case_id'] ?? 0,
            'operation_id' => $params['operation_id'] ?? 0,
        ];
        $detail = Db::name(static::$table_detail_name)->where($where)->find();
        if (empty($detail)) {
            return [];
        }

        $detail['imgs'] = array_filter(explode(',', $detail['imgs']));
        $detail['videos'] = array_filter(explode(',', $detail['videos'] ?? ''));
        $detail['operation_apparatuses'] = [];
        if ($detail['type'] == 2) {
            $operation_apparatuses = Db::name(static::$table_operation_apparatus_name)->where('operation_detail_id', $detail['id'])->select()->toArray();
            $apparatuses = [];
            foreach ($operation_apparatuses as $operation_apparatus) {
                $apparatuses[] = [
                    'id' => ($operation_apparatus['apparatus_type'] == 2)?0:$operation_apparatus['apparatus_id'],
                    'name' => $operation_apparatus['apparatus_name'],
                ];
            }
            $detail['operation_apparatuses'] = $apparatuses;
        }
        return $detail;
    }

    /**
     * 新增手术
     * @param array $params
     * @return int|string
     */
    public static function add(array $params)
    {
        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $insert = [
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'name' => $params['name'],
                'start_time' => $params['start_time'],
                'create_time' => $date,
            ];
            $id = Db::name(static::$table_name)->insertGetId($insert);
            if (!$id) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return (int)$id;
    }

    /**
     * 编辑手术
     * @param array $params
     * @return bool|string
     */
    public static function edit(array $params)
    {
        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $update = [
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'name' => $params['name'],
                'start_time' => $params['start_time'],
                'create_time' => $date,
            ];
            $where = [
                'id' => $params['id']
            ];
            $check = Db::name(static::$table_name)->where($where)->find();

            if (empty($check)) {
                throw new Exception('未知手术');
            }
            if (!Db::name(static::$table_name)->where($where)->update($update)) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }


    /** 术前add
     *  `id` bigint(20) NOT NULL AUTO_INCREMENT,
     * `case_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '病例id',
     * `hospital_id` int(11) NOT NULL DEFAULT '0' COMMENT '医院id',
     * `operation_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '手术id',
     * `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型:1=术前检查,2=手术过程,3=术后检查,0=未知',
     * `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
     * `start_time` datetime DEFAULT NULL COMMENT '开始时间',
     * `info` text COMMENT '内容(检查结果/手术过程)',
     * `videos` text COMMENT '视频',
     * `imgs` text COMMENT '图片',
     * `create_time` datetime DEFAULT NULL,
     * `update_time` datetime DEFAULT NULL,
     * `delete_time` int(11) NOT NULL DEFAULT '0',
     */
    public static function beforeAdd(array $params)
    {
        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $type = 1;
            $insert = [
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'operation_id' => $params['operation_id'],
                'type' => $type,
                'name' => $params['name'],
                'start_time' => $params['start_time'],
                'info' => $params['info'],
                'videos' => implode(',', $params['videos'] ?? []),
                'imgs' => implode(',', $params['imgs'] ?? []),
                'create_time' => $date,
            ];
            $id = Db::name(static::$table_detail_name)->insertGetId($insert);
            if (!$id) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }

    /** 术中add
     *      `case_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '病例id',
     * `hospital_id` int(11) NOT NULL DEFAULT '0' COMMENT '医院id',
     * `operation_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '手术id',
     * `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型:1=术前检查,2=手术过程,3=术后检查,0=未知',
     * `apparatus_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '器械id',
     * `apparatus_name` varchar(255) NOT NULL DEFAULT '' COMMENT '器械名称(冗余)',
     */
    public static function middleAdd(array $params)
    {

        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $type = 2;
            $insert = [
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'operation_id' => $params['operation_id'],
                'type' => $type,
                'name' => $params['name'],
                'info' => $params['info'],
                'videos' => implode(',', $params['videos'] ?? []),
                'imgs' => implode(',', $params['imgs'] ?? []),
                'create_time' => $date,
            ];
            /** 术中开始时间 */
            if (!empty($params['start_time'])) {
                $insert['start_time'] = $params['start_time'];
            }
            $id = Db::name(static::$table_detail_name)->insertGetId($insert);
            if (!$id) {
                throw new Exception('系统繁忙');
            }

            /** @desc 器械添加 @var  $operation_apparatus_inserts */
            $operation_apparatus_inserts = [];
            $apparatus_used_times = [];
            if (!empty($params['apparatus']) && is_array($params['apparatus'])) {
                foreach ($params['apparatus'] as $v) {
                    $apparatus_id = $v['id'];
                    $apparatus_type = 1;
                    if (empty($apparatus_id)) {
                        $apparatus_type = 2;
                        $apparatus_id = static::checkCustomizedApparatus($v['name']);
                        if (empty($apparatus_id)) {
                            continue;
                        }
                    }
                    $operation_apparatus_inserts[] = [
                        'operation_detail_id' => $id,
                        'case_id' => $params['case_id'],
                        'hospital_id' => $params['hospital_id'],
                        'operation_id' => $params['operation_id'],
                        'type' => $type,
                        'apparatus_type' => $apparatus_type,
                        'apparatus_id' => $v['id'],
                        'apparatus_name' => $v['name'],
                    ];
                    $apparatus_used_times[] = $apparatus_id;

                }
            }
            if (!empty($operation_apparatus_inserts)) {
                if (count($operation_apparatus_inserts) !== Db::name(static::$table_operation_apparatus_name)->insertAll($operation_apparatus_inserts)) {
                    throw new Exception('系统繁忙');
                }
            }

            /** 相关器械使用次数加1 */
            if (!empty($apparatus_used_times)) {
                static::incApparatusUsedTimes($apparatus_used_times);
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }

    /**
     * 器械使用次数自增
     * @param array $apparatusIds
     */
    protected static function incApparatusUsedTimes(array $apparatusIds)
    {
        foreach ($apparatusIds as $id) {
            Db::execute("update `sd_apparatuses` set `times`=`times`+1 where `id` = :id", ['id' => $id]);
        }
    }

    /**
     * 自定义器械查询: 有重名就返回,没有就新建
     * @param string $name
     * @return int
     */
    protected static function checkCustomizedApparatus(string $name): int
    {
        $apparatus_id = Db::name('apparatuses')->where(['type' => 2, 'name' => $name])->value('id', 0);
        if (empty($apparatus_id)) {
            $apparatus_id = Db::name('apparatuses')->insertGetId(['type' => 2, 'name' => $name, 'create_time' => date('Y-m-d H:i:s')]);
        }
        return (int)$apparatus_id;
    }

    /** 术后add */
    public static function afterAdd(array $params)
    {
        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $type = 3;
            $insert = [
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'operation_id' => $params['operation_id'],
                'type' => $type,
                'name' => $params['name'],
                'start_time' => $params['start_time'],
                'info' => $params['info'],
                'videos' => implode(',', $params['videos'] ?? []),
                'imgs' => implode(',', $params['imgs'] ?? []),
                'create_time' => $date,
            ];
            $id = Db::name(static::$table_detail_name)->insertGetId($insert);
            if (!$id) {
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
     * 术前edit
     */
    public static function beforeEdit(array $params)
    {
        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $type = 1;
            $update = [
                'name' => $params['name'],
                'start_time' => $params['start_time'],
                'info' => $params['info'],
                'videos' => implode(',', $params['videos'] ?? []),
                'imgs' => implode(',', $params['imgs'] ?? []),
                'update_time' => $date,
            ];
            $where = [
                'id' => $params['id'],
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'operation_id' => $params['operation_id'],
                'type' => $type,
            ];
            if (!Db::name(static::$table_detail_name)->where($where)->update($update)) {
                throw new Exception('系统繁忙');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }

    /** 术中edit
     *      `case_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '病例id',
     * `hospital_id` int(11) NOT NULL DEFAULT '0' COMMENT '医院id',
     * `operation_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '手术id',
     * `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型:1=术前检查,2=手术过程,3=术后检查,0=未知',
     * `apparatus_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '器械id',
     * `apparatus_name` varchar(255) NOT NULL DEFAULT '' COMMENT '器械名称(冗余)',
     */
    public static function middleEdit(array $params)
    {

        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $type = 2;
            $update = [
                'name' => $params['name'],
                'info' => $params['info'],
                'videos' => implode(',', $params['videos'] ?? []),
                'imgs' => implode(',', $params['imgs'] ?? []),
                'update_time' => $date,
            ];
            /** 术中开始时间 */
            if (!empty($params['start_time'])) {
                $update['start_time'] = $params['start_time'];
            }

            $id = $params['id'];
            $where = [
                'id' => $params['id'],
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'operation_id' => $params['operation_id'],
                'type' => $type,
            ];
            if (!Db::name(static::$table_detail_name)->where($where)->update($update)) {
                throw new Exception('系统繁忙');
            }

            /** @desc 器械添加 @var  $operation_apparatus_inserts */
            $operation_apparatus_inserts = [];
            $apparatus_used_times = [];
            if (!empty($params['apparatus']) && is_array($params['apparatus'])) {
                foreach ($params['apparatus'] as $v) {
                    $apparatus_type = 1;
                    $apparatus_id = $v['id'];
                    if (empty($apparatus_id)) {
                        $apparatus_type = 2;
                        $apparatus_id = static::checkCustomizedApparatus($v['name']);
                        if (empty($apparatus_id)) {
                            continue;
                        }
                    }
                    $operation_apparatus_inserts[] = [
                        'operation_detail_id' => $id,
                        'case_id' => $params['case_id'],
                        'hospital_id' => $params['hospital_id'],
                        'operation_id' => $params['operation_id'],
                        'type' => $type,
                        'apparatus_type' => $apparatus_type,
                        'apparatus_id' => $v['id'],
                        'apparatus_name' => $v['name'],
                    ];
                    $apparatus_used_times[] = $apparatus_id;

                }
            }
            /** 先清清除旧的器械 */
            Db::name(static::$table_operation_apparatus_name)->where('operation_detail_id', $id)->delete(true);
            /** 再添加 */
            if (!empty($operation_apparatus_inserts)) {
                if (count($operation_apparatus_inserts) !== Db::name(static::$table_operation_apparatus_name)->insertAll($operation_apparatus_inserts)) {
                    throw new Exception('系统繁忙');
                }
            }

            /** 相关器械使用次数加1 */
            if (!empty($apparatus_used_times)) {
                static::incApparatusUsedTimes($apparatus_used_times);
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }

    /** 术后edit */
    public static function afterEdit(array $params)
    {
        try {
            Db::startTrans();
            $date = date('Y-m-d H:i:s');
            $type = 3;
            $update = [
                'name' => $params['name'],
                'start_time' => $params['start_time'],
                'info' => $params['info'],
                'videos' => implode(',', $params['videos'] ?? []),
                'imgs' => implode(',', $params['imgs'] ?? []),
                'update_time' => $date,
            ];
            $where = [
                'id' => $params['id'],
                'case_id' => $params['case_id'],
                'hospital_id' => $params['hospital_id'],
                'operation_id' => $params['operation_id'],
                'type' => $type,
            ];
            if (!Db::name(static::$table_detail_name)->where($where)->update($update)) {
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
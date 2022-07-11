<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 11:56
 * Desc
 */


namespace app\api\model;

use think\facade\Db;

class Hospitals
{
    public static $table_name = 'hospitals';

    public static function map_list(): array
    {
        $where = [
            ['status', '=', 2]
        ];
        return Db::name(static::$table_name)->where($where)->field('id,name')->cache(random_int(30,180))->select()->toArray();
    }
}
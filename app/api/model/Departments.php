<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 11:47
 * Desc
 */


namespace app\api\model;
use think\facade\Db;

class Departments
{
    public static $table_name = 'departments';

    public static function map_list(): array
    {
        return Db::name(static::$table_name)->field('id,name')->cache(random_int(30,180))->select()->toArray();
    }
}
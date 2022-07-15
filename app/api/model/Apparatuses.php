<?php
/**
 * Created by caicai
 * Date 2022/7/7 0007
 * Time 11:12
 * Desc
 */


namespace app\api\model;

use think\facade\Db;
use app\common\enum\ApparatusesEnumIsTop as A_IsTop;
use app\common\enum\ApparatusesEnumType as A_Type;
class Apparatuses
{
    public static $table_name = 'apparatuses';

    /**
     * 器械列表
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public static function lists(array $params): array
    {
        $where = [
            ['a.type' ,'=', 1 ],
            ['a.status' ,'=', 1 ],
            ['a.delete_time' ,'=', 0 ],
        ];

        if (in_array($params['is_top'] ?? -1, A_IsTop::getAll())) {
            $where[] = ['a.is_top', '=', $params['is_top']];
        }

        if (!empty($params['type']) && in_array($params['type'], A_Type::getAll())) {
            $where[] = ['a.type', '=', $params['type']];
        }

        if (!empty($params['search'])) {
            $where[] = ['a.name', 'like', "%{$params['search']}%"];
        }

        $field = 'a.id,a.name,a.thumbnail,ifnull(b.name, "") as brand_name';
        $order = 'a.id desc';
        $lists = Db::name(static::$table_name)->alias('a')
            ->join('brands b', 'a.brand_id = b.id', 'LEFT')
            ->where($where)
            ->page(intval($params['page'] ?? 1))
            ->limit(intval($params['limit'] ?? 20))
            ->field($field)
            ->orderRaw($order)
            ->select()
            ->toArray();
        return  $lists;
    }

    public static function detail(array $params): array
    {
        $id = intval($params['id'] ?? 0);
        if (empty($id)) {
            return [];
        }
        $where = [
            'a.id' => $id,
            'a.status' => 1,
            'a.delete_time' => 0
        ];
        $field = "a.*,b.name as brand_name";
        $row = Db::name(static::$table_name)->alias('a')
            ->join('brands b', 'a.brand_id = b.id', 'left')
            ->where($where)
            ->fieldRaw($field)
            ->find();
        if (!empty($row)) {
            $row['imgs'] = array_filter(explode(',', $row['imgs']));
            $row['videos'] = array_filter(explode(',', $row['videos'] ??''));
            $row['factory_info'] = Db::name('factories')->where('id', $row['factory_id'])->field('*')->find() ?? [];
        }
        return $row ?? [];
    }
}
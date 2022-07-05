<?php
/**
 *
 * Apparatuses.php
 * User: ChenLong
 * DateTime: 2022-07-05 16:55:20
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 器械表 模型
 * Class Apparatuses
 * @property $id
 * @property $thumbnail
 * @property $name
 * @property $type
 * @property $factory_id
 * @property $brand_id
 * @property $times
 * @property $desc
 * @property $indication
 * @property $contraindication
 * @property $videos
 * @property $imgs
 * @property $status
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Apparatuses
 * @author chenlong <vip_chenlong@163.com>
 */
class Apparatuses extends BaseModel
{

    protected $schema = [
        'id' => 'bigint',
        'thumbnail' => 'varchar',
        'name' => 'varchar',
        'type' => 'tinyint',
        'factory_id' => 'int',
        'brand_id' => 'int',
        'times' => 'int',
        'desc' => 'text',
        'indication' => 'text',
        'contraindication' => 'text',
        'videos' => 'text',
        'imgs' => 'text',
        'status' => 'tinyint',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


<?php
/**
 *
 * Factories.php
 * User: ChenLong
 * DateTime: 2022-07-05 09:56:05
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 厂商表 模型
 * Class Factories
 * @property $id
 * @property $name
 * @property $address
 * @property $lng
 * @property $lat
 * @property $phone
 * @property $status
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Factories
 * @author chenlong <vip_chenlong@163.com>
 */
class Factories extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'address' => 'varchar',
        'lng' => 'double',
        'lat' => 'double',
        'phone' => 'varchar',
        'status' => 'tinyint',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


<?php
/**
 *
 * Hospitals.php
 * User: ChenLong
 * DateTime: 2022-07-05 09:40:46
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 医院表 模型
 * Class Hospitals
 * @property $id
 * @property $name
 * @property $contact_name
 * @property $contact_phone
 * @property $address
 * @property $lng
 * @property $lat
 * @property $desc
 * @property $status
 * @property $audit_id
 * @property $operate_id
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Hospitals
 * @author chenlong <vip_chenlong@163.com>
 */
class Hospitals extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'contact_name' => 'varchar',
        'contact_phone' => 'varchar',
        'address' => 'varchar',
        'lng' => 'double',
        'lat' => 'double',
        'desc' => 'text',
        'status' => 'tinyint',
        'audit_id' => 'int',
        'operate_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


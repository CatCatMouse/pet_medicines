<?php
/**
 *
 * Departments.php
 * User: ChenLong
 * DateTime: 2022-07-05 16:40:46
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 科室表 模型
 * Class Departments
 * @property $id
 * @property $name
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Departments
 * @author chenlong <vip_chenlong@163.com>
 */
class Departments extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


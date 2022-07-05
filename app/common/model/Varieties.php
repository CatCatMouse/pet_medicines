<?php
/**
 *
 * Varieties.php
 * User: ChenLong
 * DateTime: 2022-07-04 17:41:07
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 品种表 模型
 * Class Varieties
 * @property $id
 * @property $name
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Varieties
 * @author chenlong <vip_chenlong@163.com>
 */
class Varieties extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


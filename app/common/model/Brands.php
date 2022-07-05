<?php
/**
 *
 * Brands.php
 * User: ChenLong
 * DateTime: 2022-07-05 16:52:35
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 品牌表 模型
 * Class Brands
 * @property $id
 * @property $name
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Brands
 * @author chenlong <vip_chenlong@163.com>
 */
class Brands extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


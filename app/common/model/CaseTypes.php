<?php
/**
 *
 * CaseTypes.php
 * User: ChenLong
 * DateTime: 2022-07-05 16:47:41
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 病例分类表 模型
 * Class CaseTypes
 * @property $id
 * @property $name
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\CaseTypes
 * @author chenlong <vip_chenlong@163.com>
 */
class CaseTypes extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


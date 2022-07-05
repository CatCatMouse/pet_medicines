<?php
/**
 *
 * CaseSubjects.php
 * User: ChenLong
 * DateTime: 2022-07-05 16:50:31
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 科目表 模型
 * Class CaseSubjects
 * @property $id
 * @property $name
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\CaseSubjects
 * @author chenlong <vip_chenlong@163.com>
 */
class CaseSubjects extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


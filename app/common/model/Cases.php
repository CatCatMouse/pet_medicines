<?php
/**
 *
 * Cases.php
 * User: ChenLong
 * DateTime: 2022-07-05 17:50:26
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 病例表 模型
 * Class Cases
 * @property $id
 * @property $hospital_id
 * @property $name
 * @property $case_type_id
 * @property $case_subject_id
 * @property $variety_id
 * @property $age_year
 * @property $age_month
 * @property $sex
 * @property $desc
 * @property $videos
 * @property $imgs
 * @property $sale_id
 * @property $operate_id
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Cases
 * @author chenlong <vip_chenlong@163.com>
 */
class Cases extends BaseModel
{

    protected $schema = [
        'id' => 'bigint',
        'hospital_id' => 'int',
        'name' => 'varchar',
        'case_type_id' => 'int',
        'case_subject_id' => 'int',
        'variety_id' => 'int',
        'age_year' => 'int',
        'age_month' => 'int',
        'sex' => 'tinyint',
        'desc' => 'text',
        'videos' => 'text',
        'imgs' => 'text',
        'sale_id' => 'int',
        'operate_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


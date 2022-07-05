<?php
/**
 *
 * Cases.php
 * DateTime: 2022-07-05 17:50:27
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 病例表 验证器
 * Class Cases
 * @package app\common\validate\Cases
 */
class Cases extends BaseValidate
{
    protected $rule = [
        'id|病例表' => 'require|number',
        'hospital_id|所属医院id' => 'require|number',
        'name|名称' => 'require',
        'case_type_id|病例分类id' => 'require|number',
        'case_subject_id|病例科目id' => 'require|number',
        'variety_id|品种id' => 'require|number',
        'age_year|年龄年份' => 'require|number',
        'age_month|年龄月份' => 'require|number',
        'sex|性别' => 'require|number|in:0,1,2',
        'desc|描述' => 'require',
        'videos|视频' => 'require',
        'imgs|图片' => 'require',
        'operate_id|操作人id' => 'require|number',
    ];

    protected $scene = [
        'create' => ['hospital_id', 'name', 'case_type_id', 'case_subject_id', 'variety_id', 'age_year', 'age_month', 'sex', 'desc',  ],
        'update' => ['id', 'hospital_id', 'name', 'case_type_id', 'case_subject_id', 'variety_id', 'age_year', 'age_month', 'sex', 'desc', ],
    ];
}

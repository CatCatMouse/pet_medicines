<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 15:44
 * Desc
 */


namespace app\api\validate;

use think\Validate;

class CaseValidate extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'case_type_id' => 'require',
        'case_subject_id' => 'require',
        'variety_id' => 'require',
        'age_month' => 'require',
        'sex' => 'require',
        'hospital_id' => 'require',
        'attending_physicians' => 'require',
        'desc' => 'require',
    ];

    protected $message = [
        'name.require' => '请输入医院名称',
        'case_type_id.require' => '请选择分类',
        'case_subject_id.require' => '请选择科目',
        'variety_id.require' => '请选择品总',
        'age_month.require' => '请选择年龄',
        'sex.require' => '请选择性别',
        'hospital_id.require' => '请选择医院',
        'attending_physicians.require' => '请选择主治医师',
        'desc.require' => '请输入病情描述',
    ];

    protected $scene = [
        'add' => ['name', 'case_type_id', 'case_subject_id', 'age_month', 'sex', 'hospital_id', 'attending_physicians', 'desc'],
        'edit' => ['id', 'name', 'case_type_id', 'case_subject_id', 'age_month', 'sex', 'hospital_id', 'attending_physicians', 'desc']
    ];
}
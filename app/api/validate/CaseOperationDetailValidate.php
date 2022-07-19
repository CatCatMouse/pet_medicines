<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 15:44
 * Desc
 */


namespace app\api\validate;

use think\Validate;

class CaseOperationDetailValidate extends Validate
{
    protected $rule = [
        'id' => 'require',
        'case_id' => 'require',
        'hospital_id' => 'require',
        'operation_id' => 'require',
        'name' => 'require',
        'start_time' => 'require',
        'info' => 'require',
        'apparatus' => 'require',
    ];

    protected $message = [
        'case_id.require' => '病例空',
        'hospital_id.require' => '医院空',
        'operation_id.require' => '手术空',
        'name.require' => '请输入名称',
        'start_time.require' => '请选择开始时间',
        'info.require' => '请输入手术结果',
    ];

    protected $scene = [
        'before_add' => ['case_id', 'hospital_id', 'operation_id', 'name', 'start_time', 'info'],
        'middle_add' => ['case_id', 'hospital_id', 'operation_id', 'name', 'info', 'apparatus'],
        'after_add' => ['case_id', 'hospital_id', 'operation_id', 'name', 'start_time', 'info'],
        'before_edit' => ['id', 'case_id', 'hospital_id', 'operation_id', 'name', 'start_time', 'info'],
        'middle_edit' => ['id', 'case_id', 'hospital_id', 'operation_id', 'name', 'info', 'apparatus'],
        'after_edit' => ['id', 'case_id', 'hospital_id', 'operation_id', 'name', 'start_time', 'info'],
    ];
}
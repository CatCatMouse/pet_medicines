<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 15:44
 * Desc
 */


namespace app\api\validate;

use think\Validate;

class CaseOperationValidate extends Validate
{
    protected $rule = [
        'id' => 'require',
        'case_id' => 'require',
        'hospital_id' => 'require',
        'name' => 'require',
        'start_time' => 'require',
    ];

    protected $message = [
        'name.require' => '请输入名称',
        'case_id.require' => '病例空',
        'hospital_id.require' => '医院空',
        'start_time.require' => '请选择开始时间',
    ];

    protected $scene = [
        'add' => ['name', 'case_id', 'hospital_id', 'start_time'],
        'edit' => ['id', 'name', 'case_id', 'hospital_id', 'start_time'],
    ];
}
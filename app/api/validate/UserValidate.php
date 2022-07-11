<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 14:19
 * Desc
 */


namespace app\api\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'user_name' => 'require',
        'hospital_id' => 'require',
        'department_id' => 'require',
    ];

    protected $message = [
        'user_name.require' => '请输入姓名',
        'hospital_id.require' => '请选择医院',
        'department_id.require' => '请选择科室',
    ];

    protected $scene = [
        'doctorApply' => ['user_name', 'hospital_id', 'department_id']
    ];
}
<?php
/**
 * Created by caicai
 * Date 2022/7/11 0011
 * Time 15:44
 * Desc
 */


namespace app\api\validate;

use think\Validate;

class HospitalValidate extends Validate
{
    protected $rule = [
        'name' => 'require',
        'contact_name' => 'require',
        'contact_phone' => 'require',
        'lng' => 'require|float',
        'lat' => 'require|float',
        'address' => 'require',
        'desc' => 'require',
    ];

    protected $message = [
        'name.require' => '请输入医院名称',
        'contact_name.require' => '请输入联系人',
        'contact_phone.require' => '请输入联系电话',
        'lng.require' => '请定位',
        'lng.float' => '请定位',
        'lat.require' => '请定位',
        'lat.float' => '请定位',
        'address.require' => '请输入医院详细地址',
        'desc.require' => '请输入合作内容',
    ];

    protected $scene = [
        'hospitalApply'  => ['name', 'contact_name', 'contact_phone', 'lng', 'lat', 'address', 'desc']
    ];
}
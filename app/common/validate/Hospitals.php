<?php
/**
 *
 * Hospitals.php
 * DateTime: 2022-07-05 09:40:47
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 医院表 验证器
 * Class Hospitals
 * @package app\common\validate\Hospitals
 */
class Hospitals extends BaseValidate
{
    protected $rule = [
        'id|医院表' => 'require|number',
        'name|医院名称' => 'require',
        'contact_name|联系人' => 'require',
        'contact_phone|联系电话' => 'require|mobile',
        'address|地址' => 'require',
        'desc|合作内容' => 'require',
//        'status|状态' => 'require|number|in:1,2,-1',
        'audit_id|审核人id' => 'require|number',
        'operate_id|申请人id' => 'require|number',
    ];

    protected $scene = [
        'create' => ['name', 'contact_name', 'contact_phone', 'address', 'desc', ],
        'update' => ['id', 'name', 'contact_name', 'contact_phone', 'address', 'desc', ],
    ];
}

<?php
/**
 *
 * Factories.php
 * DateTime: 2022-07-05 09:56:06
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 厂商表 验证器
 * Class Factories
 * @package app\common\validate\Factories
 */
class Factories extends BaseValidate
{
    protected $rule = [
        'id|厂商表' => 'require|number',
        'name|厂商名称' => 'require',
        'address|地址' => 'require',
        'phone|联系方式' => 'require|mobile',
        'status|状态' => 'require|number|in:1,-1',
    ];

    protected $scene = [
        'create' => ['name', 'address', 'phone', 'status'],
        'update' => ['id', 'name', 'address', 'phone', 'status'],
    ];
}

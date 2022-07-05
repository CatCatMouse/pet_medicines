<?php
/**
 *
 * Varieties.php
 * DateTime: 2022-07-04 17:41:15
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 品种表 验证器
 * Class Varieties
 * @package app\common\validate\Varieties
 */
class Varieties extends BaseValidate
{
    protected $rule = [
        'id|品种表' => 'require|number',
        'name|名称' => 'require',
    ];

    protected $scene = [
        'create' => ['name'],
        'update' => ['id', 'name'],
    ];
}

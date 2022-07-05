<?php
/**
 *
 * Departments.php
 * DateTime: 2022-07-05 16:40:47
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 科室表 验证器
 * Class Departments
 * @package app\common\validate\Departments
 */
class Departments extends BaseValidate
{
    protected $rule = [
        'id|科室表' => 'require|number',
        'name|科室名称' => 'require',
    ];

    protected $scene = [
        'create' => ['name'],
        'update' => ['id', 'name'],
    ];
}

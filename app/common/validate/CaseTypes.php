<?php
/**
 *
 * CaseTypes.php
 * DateTime: 2022-07-05 16:47:41
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 病例分类表 验证器
 * Class CaseTypes
 * @package app\common\validate\CaseTypes
 */
class CaseTypes extends BaseValidate
{
    protected $rule = [
        'id|病例分类表' => 'require|number',
        'name|名称' => 'require',
    ];

    protected $scene = [
        'create' => ['name'],
        'update' => ['id', 'name'],
    ];
}

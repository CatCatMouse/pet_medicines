<?php
/**
 *
 * CaseSubjects.php
 * DateTime: 2022-07-05 16:50:31
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 科目表 验证器
 * Class CaseSubjects
 * @package app\common\validate\CaseSubjects
 */
class CaseSubjects extends BaseValidate
{
    protected $rule = [
        'id|科目表' => 'require|number',
        'name|名称' => 'require',
    ];

    protected $scene = [
        'create' => ['name'],
        'update' => ['id', 'name'],
    ];
}

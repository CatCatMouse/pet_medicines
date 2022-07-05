<?php
/**
 *
 * Brands.php
 * DateTime: 2022-07-05 16:52:36
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 品牌表 验证器
 * Class Brands
 * @package app\common\validate\Brands
 */
class Brands extends BaseValidate
{
    protected $rule = [
        'id|品牌表' => 'require|number',
        'name|品牌名' => 'require',
    ];

    protected $scene = [
        'create' => ['name'],
        'update' => ['id', 'name'],
    ];
}

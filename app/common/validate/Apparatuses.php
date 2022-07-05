<?php
/**
 *
 * Apparatuses.php
 * DateTime: 2022-07-05 16:55:22
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 器械表 验证器
 * Class Apparatuses
 * @package app\common\validate\Apparatuses
 */
class Apparatuses extends BaseValidate
{
    protected $rule = [
        'id|器械表' => 'require|number',
        'thumbnail|缩略图' => 'require',
        'name|名称' => 'require',
        'type|类型' => 'require|number|in:1,2',
        'factory_id|厂商id' => 'require|number',
        'brand_id|品牌id' => 'require|number',
        'times|使用次数' => 'require|number',
        'desc|介绍' => 'require',
        'indication|适应症' => 'require',
        'contraindication|禁忌症' => 'require',
        'videos|视频' => 'require',
        'imgs|图片' => 'require',
        'status|状态' => 'require|number|in:1,-1',
    ];

    protected $scene = [
        'create' => ['thumbnail', 'name', 'type', 'factory_id', 'brand_id', 'times', 'desc', 'indication', 'contraindication',  'status'],
        'update' => ['id', 'thumbnail', 'name', 'type', 'factory_id', 'brand_id', 'times', 'desc', 'indication', 'contraindication', 'status'],
    ];
}

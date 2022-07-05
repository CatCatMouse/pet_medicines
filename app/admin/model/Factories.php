<?php
/**
 *
 * Factories.php
 * DateTime: 2022-07-05 09:56:05
 */

namespace app\admin\model;

use app\common\model\Factories as commonFactories;
use app\common\enum\FactoriesEnumStatus;

/**
 * 厂商表 模型
 * Class Factories
 * @package app\admin\model\Factories
 */
class Factories extends commonFactories
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getStatusAttr($value): string
    {
        return FactoriesEnumStatus::create($value)->getDes();
    }


}

<?php
/**
 *
 * Apparatuses.php
 * DateTime: 2022-07-05 16:55:20
 */

namespace app\admin\model;

use app\common\model\Apparatuses as commonApparatuses;
use app\common\enum\ApparatusesEnumType;
use app\common\enum\ApparatusesEnumStatus;

/**
 * 器械表 模型
 * Class Apparatuses
 * @package app\admin\model\Apparatuses
 */
class Apparatuses extends commonApparatuses
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getTypeAttr($value): string
    {
        return ApparatusesEnumType::create($value)->getDes();
    }

    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getStatusAttr($value): string
    {
        return ApparatusesEnumStatus::create($value)->getDes();
    }


}

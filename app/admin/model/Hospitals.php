<?php
/**
 *
 * Hospitals.php
 * DateTime: 2022-07-05 09:40:46
 */

namespace app\admin\model;

use app\common\model\Hospitals as commonHospitals;
use app\common\enum\HospitalsEnumStatus;

/**
 * 医院表 模型
 * Class Hospitals
 * @package app\admin\model\Hospitals
 */
class Hospitals extends commonHospitals
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getStatusAttr($value): string
    {
        return HospitalsEnumStatus::create($value)->getDes();
    }


}

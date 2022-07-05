<?php
/**
 *
 * Cases.php
 * DateTime: 2022-07-05 17:50:26
 */

namespace app\admin\model;

use app\common\model\Cases as commonCases;
use app\common\enum\CasesEnumSex;

/**
 * 病例表 模型
 * Class Cases
 * @package app\admin\model\Cases
 */
class Cases extends commonCases
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getSexAttr($value): string
    {
        return CasesEnumSex::create($value)->getDes();
    }


}

<?php
/**
* CasesEnumSex.php
* Date: 2022-07-05 17:50:26
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 病例表
* Class CasesEnumSex
* @package app\common\enum\CasesEnumSex
*/
class CasesEnumSex extends Enum
{

    const WEIZHI = 0;
    const GONG = 1;
    const MU = 2;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::WEIZHI => Layui::tag()->gray("未知"),
            self::GONG => Layui::tag()->blue("公"),
            self::MU => Layui::tag()->orange("母"),
        ];
    }



}
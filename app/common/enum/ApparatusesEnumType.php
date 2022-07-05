<?php
/**
* ApparatusesEnumType.php
* Date: 2022-07-05 16:55:20
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 器械表
* Class ApparatusesEnumType
* @package app\common\enum\ApparatusesEnumType
*/
class ApparatusesEnumType extends Enum
{

    const ZHENGCHANG = 1;
    const ZIDINGYI = 2;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::ZHENGCHANG => Layui::tag()->red("正常"),
            self::ZIDINGYI => Layui::tag()->black("自定义"),
        ];
    }



}
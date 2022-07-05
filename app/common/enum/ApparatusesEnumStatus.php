<?php
/**
* ApparatusesEnumStatus.php
* Date: 2022-07-05 16:55:20
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 器械表
* Class ApparatusesEnumStatus
* @package app\common\enum\ApparatusesEnumStatus
*/
class ApparatusesEnumStatus extends Enum
{

    const ZHENGCHANG = 1;
    const XIAJIA = -1;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::ZHENGCHANG => Layui::tag()->green("正常"),
            self::XIAJIA => Layui::tag()->red("下架"),
        ];
    }



}
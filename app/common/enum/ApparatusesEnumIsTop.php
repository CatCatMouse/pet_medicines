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
* Class ApparatusesEnumIsTop
* @package app\common\enum\ApparatusesEnumIsTop
*/
class ApparatusesEnumIsTop extends Enum
{

    const YES = 1;
    const NO = 0;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::YES => Layui::tag()->green("推荐"),
            self::NO => Layui::tag()->red(""),
        ];
    }



}
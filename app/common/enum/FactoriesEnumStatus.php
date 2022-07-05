<?php
/**
* FactoriesEnumStatus.php
* Date: 2022-07-05 09:56:05
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 厂商表
* Class FactoriesEnumStatus
* @package app\common\enum\FactoriesEnumStatus
*/
class FactoriesEnumStatus extends Enum
{

    const ZHENGCHANG = 1;
    const DONGJIE = -1;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::ZHENGCHANG => Layui::tag()->rim("正常"),
            self::DONGJIE => Layui::tag()->red("冻结"),
        ];
    }



}
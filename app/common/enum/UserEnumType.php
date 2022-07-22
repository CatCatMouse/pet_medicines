<?php
/**
* UserEnumType.php
* Date: 2022-07-05 09:32:53
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 用户表
* Class UserEnumType
* @package app\common\enum\UserEnumType
*/
class UserEnumType extends Enum
{

    const YOUKE = 1;
    const XIAOSHOU = 2;
    const YISHENG = 3;
    const YIYUAN = 4;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::YOUKE => Layui::tag()->black("游客"),
            self::XIAOSHOU => Layui::tag()->blue("销售"),
            self::YISHENG => Layui::tag()->orange("医生"),
            self::YIYUAN => Layui::tag()->green("医院"),
        ];
    }



}
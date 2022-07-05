<?php
/**
* UserEnumStatus.php
* Date: 2022-07-05 09:32:53
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 用户表
* Class UserEnumStatus
* @package app\common\enum\UserEnumStatus
*/
class UserEnumStatus extends Enum
{

    const ZHENGCHANG = 1;
    const FENGJIN = 2;
    const ZHUXIAO = 3;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::ZHENGCHANG => Layui::tag()->black("正常"),
            self::FENGJIN => Layui::tag()->cyan("封禁"),
            self::ZHUXIAO => Layui::tag()->gray("注销"),
        ];
    }



}
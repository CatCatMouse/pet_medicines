<?php
/**
* HospitalsEnumStatus.php
* Date: 2022-07-05 09:40:46
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 医院表
* Class HospitalsEnumStatus
* @package app\common\enum\HospitalsEnumStatus
*/
class HospitalsEnumStatus extends Enum
{

    const DAISHENHE = 1;
    const SHENHETONGGUO = 2;
    const SHENHESHIBAI = -1;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::DAISHENHE => Layui::tag()->cyan("待审核"),
            self::SHENHETONGGUO => Layui::tag()->orange("审核通过"),
            self::SHENHESHIBAI => Layui::tag()->black("审核失败"),
        ];
    }



}
<?php
/**
 *
 * User.php
 * DateTime: 2022-07-05 09:32:53
 */

namespace app\admin\model;

use app\common\model\User as commonUser;
use app\common\enum\UserEnumType;
use app\common\enum\UserEnumStatus;

/**
 * 用户表 模型
 * Class User
 * @package app\admin\model\User
 */
class User extends commonUser
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getTypeAttr($value): string
    {
        return UserEnumType::create($value)->getDes();
    }

    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getStatusAttr($value): string
    {
        return UserEnumStatus::create($value)->getDes();
    }


}

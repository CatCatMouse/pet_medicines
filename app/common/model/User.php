<?php
/**
 *
 * User.php
 * User: ChenLong
 * DateTime: 2022-07-05 09:32:53
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 用户表 模型
 * Class User
 * @property $id
 * @property $pid
 * @property $type
 * @property $nickname
 * @property $name
 * @property $phone
 * @property $password
 * @property $avatar
 * @property $account
 * @property $id_card
 * @property $wx_openid
 * @property $invite_code
 * @property $zfb_user_id
 * @property $vip_level
 * @property $balance
 * @property $integral
 * @property $email
 * @property $status
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\User
 * @author chenlong <vip_chenlong@163.com>
 */
class User extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'pid' => 'int',
        'type' => 'tinyint',
        'nickname' => 'varchar',
        'name' => 'varchar',
        'phone' => 'char',
        'password' => 'varchar',
        'avatar' => 'varchar',
        'account' => 'varchar',
        'id_card' => 'char',
        'wx_openid' => 'char',
        'invite_code' => 'varchar',
        'zfb_user_id' => 'char',
        'vip_level' => 'tinyint',
        'balance' => 'int',
        'integral' => 'int',
        'email' => 'varchar',
        'status' => 'tinyint',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];

}


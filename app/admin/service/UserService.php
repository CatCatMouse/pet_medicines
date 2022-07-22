<?php
/**
* User.php
* DateTime: 2022-07-05 09:33:03
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\User as MyModel;

/**
* 用户表 服务层
* Class UserService
* @package app\admin\service\UserService
*/
class UserService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.pid,i.type type_true,i.type,i.type as type_id,i.nickname,i.name,i.phone,i.avatar,i.account,i.id_card,i.wx_openid,i.invite_code,i.zfb_user_id,i.vip_level,i.balance,i.integral,i.email,i.status status_true,i.status,i.create_time,i.update_time,i.delete_time')
            ;

        return $service->setModel($model)->getListsData();
    }

}

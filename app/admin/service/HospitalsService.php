<?php
/**
* Hospitals.php
* DateTime: 2022-07-05 09:40:48
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\Hospitals as MyModel;

/**
* 医院表 服务层
* Class HospitalsService
* @package app\admin\service\HospitalsService
*/
class HospitalsService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.name,i.contact_name,i.contact_phone,i.address,i.lng,i.lat,i.status status_true,i.status,administrators.name administrators_name,i.audit_id,user.name user_name,i.operate_id,i.create_time,i.update_time,i.delete_time')
            ->join('administrators', 'i.audit_id = administrators.id ', 'left')
            ->join('user', 'i.operate_id = user.id ', 'left');

        return $service->setModel($model)->getListsData();
    }

}

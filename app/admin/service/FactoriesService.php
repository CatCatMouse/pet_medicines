<?php
/**
* Factories.php
* DateTime: 2022-07-05 09:56:07
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\Factories as MyModel;

/**
* 厂商表 服务层
* Class FactoriesService
* @package app\admin\service\FactoriesService
*/
class FactoriesService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.name,i.address,i.lng,i.lat,i.phone,i.status status_true,i.status,i.create_time,i.update_time,i.delete_time')
            ;

        return $service->setModel($model)->getListsData();
    }

}

<?php
/**
* Apparatuses.php
* DateTime: 2022-07-05 16:55:23
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\Apparatuses as MyModel;

/**
* 器械表 服务层
* Class ApparatusesService
* @package app\admin\service\ApparatusesService
*/
class ApparatusesService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.thumbnail,i.name,i.type type_true,i.type,factories.name factories_name
        ,i.factory_id,brands.name brands_name,i.brand_id,i.times,i.imgs,i.status status_true,i.status,i.create_time,i.update_time,i.delete_time
        ,i.is_top
        ')
            ->join('factories', 'i.factory_id = factories.id ', 'left')
            ->join('brands', 'i.brand_id = brands.id ', 'left');

        return $service->setModel($model)->getListsData();
    }

}

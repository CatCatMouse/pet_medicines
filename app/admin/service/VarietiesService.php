<?php
/**
* Varieties.php
* DateTime: 2022-07-04 17:41:16
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\common\model\Varieties as MyModel;

/**
* 品种表 服务层
* Class VarietiesService
* @package app\admin\service\VarietiesService
*/
class VarietiesService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.name,i.create_time,i.update_time,i.delete_time')
            ;

        return $service->setModel($model)->getListsData();
    }

}

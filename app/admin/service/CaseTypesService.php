<?php
/**
* CaseTypes.php
* DateTime: 2022-07-05 16:47:42
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\common\model\CaseTypes as MyModel;

/**
* 病例分类表 服务层
* Class CaseTypesService
* @package app\admin\service\CaseTypesService
*/
class CaseTypesService extends AdminBaseService
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

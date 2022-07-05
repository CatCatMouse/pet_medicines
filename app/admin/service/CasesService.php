<?php
/**
* Cases.php
* DateTime: 2022-07-05 17:50:30
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\Cases as MyModel;

/**
* 病例表 服务层
* Class CasesService
* @package app\admin\service\CasesService
*/
class CasesService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,hospitals.name hospitals_name,i.hospital_id,i.name,case_types.name case_types_name,i.case_type_id,case_subjects.name case_subjects_name,i.case_subject_id,varieties.name varieties_name,i.variety_id,i.age_year,i.age_month,i.sex sex_true,i.sex,i.imgs,user.name sale_name,i.sale_id,u.name operate_name,i.operate_id,i.create_time,i.update_time,i.delete_time')
            ->join('hospitals', 'i.hospital_id = hospitals.id ', 'left')
            ->join('case_types', 'i.case_type_id = case_types.id ', 'left')
            ->join('case_subjects', 'i.case_subject_id = case_subjects.id ', 'left')
            ->join('varieties', 'i.variety_id = varieties.id ', 'left')
            ->join('user', 'i.sale_id = user.id ', 'left')
            ->join('user u', 'i.operate_id = u.id ', 'left');

        return $service->setModel($model)->getListsData();
    }

}

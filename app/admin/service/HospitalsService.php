<?php
/**
* Hospitals.php
* DateTime: 2022-07-05 09:40:48
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\BaseModel;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\Hospitals as MyModel;
use think\Exception;

use app\common\enum\UserEnumType as T;
use think\exception\HttpResponseException;
use think\facade\Db;

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

    public function dataSave(array $data, BaseModel $model)
    {
        $model->startTrans();
        try {
            $this->beforeWrite($data);
            $save_type = 'create';

            if (isset($data[$model->getPk()])) {
                $model = $model->find($data[$model->getPk()]);
                unset($data[$model->getPk()]);
                $save_type = 'update';

                //审核
                if($model->getData('status') == 1 && $data['status'] != 1) {
                    $data['audit_id'] = 1;
                    switch ($data['status']) {
                        case 2: //通过
                            if (!Db::name('user')->where(['id' => $data['operate_id'], 'type' => T::YOUKE, 'hospital_id' => 0])
                            ->update(['type' => T::YIYUAN, 'hospital_id' => $model[$model->getPk()]])) {
                                throw new \Exception('审核失败');
                            }
                            break;
                        case -1: //拒绝
                            break;
                    }
                }

            }
            $model->save($data);

            $this->afterWrite($save_type, array_merge($data, [$model->getPk() => $model[$model->getPk()]]));
            $model->commit();
        } catch (\Throwable $exception) {
            $model->rollback();
            if ($exception instanceof HttpResponseException) {
                throw $exception;
            }
            throw new SdException($exception->getMessage());
        }
    }
}

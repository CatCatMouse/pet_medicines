<?php
/**
 * Hospitals.php
 * User: ChenLong
 * DateTime: 2022-07-05 09:40:46
 */

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\SdException;
use app\admin\service\HospitalsService as MyService;
use app\admin\model\Hospitals as MyModel;
use app\admin\page\HospitalsPage as MyPage;
use app\common\validate\Hospitals as MyValidate;

/**
 * 医院表 控制器
 * Class Hospitals
 * @package app\admin\controller\Hospitals
 * @author chenlong <vip_chenlong@163.com>
 */
class Hospitals extends Admin
{

    /**
     * @title("医院表列表")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function index(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::index_($service, $model, $page);
    }
    
            
    /**
     * @title("新增医院表")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function create(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::create_($service, $model, $page, MyValidate::class);
    }

            
    /**
     * @title("更新医院表")
     * @param MyService $service
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws SdException
     * @throws \ReflectionException
     */
    public function update(MyService $service, MyModel $model, MyPage $page)
    {
        return parent::update_($service, $model, $page, MyValidate::class);
    }

            
    /**
     * @title("删除医院表")
     * @param MyService $service
     * @param MyModel $model
     * @return \think\response\Json
     * @throws SdException
     */
    public function delete(MyService $service, MyModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }

}

<?php
/**
 * Cases.php
 * User: ChenLong
 * DateTime: 2022-07-05 17:50:26
 */

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\SdException;
use app\admin\service\CasesService as MyService;
use app\admin\model\Cases as MyModel;
use app\admin\page\CasesPage as MyPage;
use app\common\validate\Cases as MyValidate;

/**
 * 病例表 控制器
 * Class Cases
 * @package app\admin\controller\Cases
 * @author chenlong <vip_chenlong@163.com>
 */
class Cases extends Admin
{

    /**
     * @title("病例表列表")
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
     * @title("新增病例表")
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
     * @title("更新病例表")
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
     * @title("删除病例表")
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

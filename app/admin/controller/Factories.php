<?php
/**
 * Factories.php
 * User: ChenLong
 * DateTime: 2022-07-05 09:56:05
 */

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\SdException;
use app\admin\service\FactoriesService as MyService;
use app\admin\model\Factories as MyModel;
use app\admin\page\FactoriesPage as MyPage;
use app\common\validate\Factories as MyValidate;

/**
 * 厂商表 控制器
 * Class Factories
 * @package app\admin\controller\Factories
 * @author chenlong <vip_chenlong@163.com>
 */
class Factories extends Admin
{

    /**
     * @title("厂商表列表")
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
     * @title("新增厂商表")
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
     * @title("更新厂商表")
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
     * @title("删除厂商表")
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

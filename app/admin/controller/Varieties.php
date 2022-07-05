<?php
/**
 * Varieties.php
 * User: ChenLong
 * DateTime: 2022-07-04 17:41:07
 */

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\SdException;
use app\admin\service\VarietiesService as MyService;
use app\common\model\Varieties as MyModel;
use app\admin\page\VarietiesPage as MyPage;
use app\common\validate\Varieties as MyValidate;

/**
 * 品种表 控制器
 * Class Varieties
 * @package app\admin\controller\Varieties
 * @author chenlong <vip_chenlong@163.com>
 */
class Varieties extends Admin
{

    /**
     * @title("品种表列表")
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
     * @title("新增品种表")
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
     * @title("更新品种表")
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
     * @title("删除品种表")
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

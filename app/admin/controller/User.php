<?php
/**
 * User.php
 * User: ChenLong
 * DateTime: 2022-07-05 09:32:53
 */

namespace app\admin\controller;

use app\admin\page\UserPage;
use app\common\controller\Admin;
use app\common\ResponseJson;
use app\common\SdException;
use app\admin\service\UserService as MyService;
use app\admin\model\User as MyModel;
use app\admin\page\UserPage as MyPage;
use app\common\validate\User as MyValidate;
use think\Exception;
use think\facade\Db;
use app\common\enum\UserEnumType as T;

/**
 * 用户表 控制器
 * Class User
 * @package app\admin\controller\User
 * @author chenlong <vip_chenlong@163.com>
 */
class User extends Admin
{

    /**
     * @title("用户表列表")
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
     * @title("新增用户表")
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
     * @title("更新用户表")
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
     * @title("删除用户表")
     * @param MyService $service
     * @param MyModel $model
     * @return \think\response\Json
     * @throws SdException
     */
    public function delete(MyService $service, MyModel $model): \think\response\Json
    {
        return parent::delete_($service, $model);
    }

    /**
     * 设为销售
     * @param MyModel $model
     * @param MyPage $page
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function set_sale(MyModel $model, UserPage $page)
    {
        if ($this->request->isPost()) {
            $data = data_filter($this->request->post());
            $this->validate($data, [
                'factory_id' => 'require',
                'hospital_ids' => 'require',
            ], [
                'factory_id.require' => '请选择所属厂商',
                'hospital_ids.require' => '请选择负责医院',
            ]);
            try {
                Db::startTrans();
                $date = date('Y-m-d H:i:s');
                $update = [
                    'factory_id' => $data['factory_id'],
                    'type' => T::XIAOSHOU,
                    'update_time' => $date,
                ];
                $sale_hospital_inserts = [];
                foreach (explode(',', $data['hospital_ids']) as $v) {
                    if (Db::name('sale_hospitals')->where('hospital_id', $v)->find()) {
                        throw new Exception('医院已有负责人');
                    }
                    $sale_hospital_inserts[] = [
                        'hospital_id' => $v,
                        'user_id' => $data['id'],
                        'create_time' => $date,
                    ];
                }

                //更新
                if (!Db::name('user')->where(['id' => $data['id'], 'type' => T::YOUKE, 'factory_id' => 0])->update($update)) {
                    throw new Exception('设置失败');
                }
                //插入
                if (!empty($sale_hospital_inserts)) {
                    if (count($sale_hospital_inserts) != Db::name('sale_hospitals')->insertAll($sale_hospital_inserts)) {
                        throw new Exception('设置失败2');
                    }
                }
                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                return ResponseJson::fail($e->getMessage());
            }
            return ResponseJson::success();
        }

        $data = $model->findOrEmpty($this->request->param('id', 0))->getData();
        if (empty($data)) {
            $data->hospital_ids = implode(array_column(Db::name('sale_hospitals')->where('user_id', $data->id)->field('id')->select()->toArray() ?? [], 'id'));
        }
        return view($page->form_template, [
            'form' => $page->setSale('', $data)
        ]);

    }
}

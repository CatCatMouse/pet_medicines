<?php
/**
 * User.php
 * Date: 2022-07-05 09:33:02
 */

namespace app\admin\page;

use app\common\BasePage;
use app\common\model\Factories;
use app\common\model\Hospitals;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\common\enum\UserEnumType;
use app\common\enum\UserEnumStatus;
use think\facade\Db;


/**
 * 用户表
 * Class UserPage
 * @package app\admin\page\UserPage
 */
class UserPage extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('ID', 'id'),
            Column::normal('角色', 'type'),
            Column::normal('姓名', 'name'),
            Column::normal('微信昵称', 'nickname'),
            Column::normal('微信手机号', 'phone'),
            Column::normal('微信头像', 'avatar')->showImage(),
            Column::normal('微信openid', 'wx_openid'),
            Column::normal('状态', 'status'),
            Column::normal('注册时间', 'create_time'),
        ]);

        // 更多处理事件及其他设置，$table->setHandleAttr() 可设置操作栏的属性
        $table->removeBarEvent(['delete', 'create']);
        $table->addEvent()->setNormalBtn('设为销售')->setJs(EventHandle::openPage([url('set_sale'), 'id'], '设为销售')->popUps())->setWhere('d.type_id == 1');
        return $table;
    }

    /**
     * 生成表单的数据
     * @param string $scene
     * @param array $default_data
     * @return Form
     */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::select('type', '角色')->options(UserEnumType::getMap(true))->inputAttr('-', [
                'disabled' => true
            ]),
            FormUnit::text('nickname', '昵称'),
            FormUnit::text('name', '姓名'),
            FormUnit::text('phone', '手机号'),
            FormUnit::text('wx_openid', '微信openid')->inputAttr('-', [
                'disabled' => true
            ]),
            FormUnit::radio('status', '状态')->options(UserEnumStatus::getMap(true)),
        ];

        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }


    /**
     * 创建列表搜索表单的数据
     * @return Form
     */
    public function listSearchFormData(): Form
    {
        $form_data = [
            FormUnit::group(
                FormUnit::select('i.type')->placeholder('角色')->options(UserEnumType::getMap(true)),
                FormUnit::text('i.name%%')->placeholder('姓名'),
                FormUnit::text('i.phone%%')->placeholder('手机号'),
            ),
        ];

        return Form::create($form_data)->setSearchSubmitElement();
    }


    /**
     * 生成表单的数据
     * @param string $scene
     * @param array $default_data
     * @return Form
     */
    public function setSale(string $scene, array $default_data = []): Form
    {
        $factories = Factories::column('name', 'id');
        $hospitals = Hospitals::column('name', 'id');
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::select('type', '角色')->options(UserEnumType::getMap(true))->inputAttr('-', [
                'disabled' => true
            ]),
            FormUnit::text('nickname', '昵称')->inputAttr('-', [
                'disabled' => true
            ]),
            FormUnit::text('name', '姓名')->inputAttr('-', [
                'disabled' => true
            ]),
            FormUnit::text('phone', '手机号')->inputAttr('-', [
                'disabled' => true
            ]),
            !empty($default_data['factory_id'])
                ? FormUnit::select('factory_id', '所属厂商')->options($factories)->inputAttr('-', ['disabled' => true])->required()
                : FormUnit::select('factory_id', '所属厂商')->options($factories)->required(),
            FormUnit::selects('hospital_ids', '负责医院')->options($hospitals)->required()
        ];


        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }
}

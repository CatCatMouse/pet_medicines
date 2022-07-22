<?php
/**
 * Hospitals.php
 * Date: 2022-07-05 09:40:47
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\common\enum\HospitalsEnumStatus;
use app\admin\model\system\Administrators;
use app\admin\model\User;


/**
 * 医院表
 * Class HospitalsPage
 * @package app\admin\page\HospitalsPage
 */
class HospitalsPage extends BasePage
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
            Column::normal('医院名称', 'name'),
            Column::normal('联系人', 'contact_name'),
            Column::normal('联系电话', 'contact_phone'),
            Column::normal('地址', 'address'),
            Column::normal('经度', 'lng'),
            Column::normal('纬度', 'lat'),
            Column::normal('状态', 'status'),
//            Column::normal('审核人id', 'administrators_name'),
//            Column::normal('申请人id', 'user_name'),
            Column::normal('创建时间', 'create_time'),
//            Column::normal('', 'update_time'),
//            Column::normal('', 'delete_time'),
        ]);

        // 更多处理事件及其他设置，$table->setHandleAttr() 可设置操作栏的属性

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
            FormUnit::text('name', '医院名称'),
            FormUnit::text('contact_name', '联系人'),
            FormUnit::text('contact_phone', '联系电话'),
            FormUnit::text('lng', '经度'),
            FormUnit::text('lat', '纬度'),
            FormUnit::text('address', '地址'),
            FormUnit::uEditor('desc', '合作内容'),
            (!empty($default_data) && $default_data['status'] <> 1)
                ? FormUnit::text('', '状态')->defaultValue(HospitalsEnumStatus::getMap(true)[$default_data['status']])->inputAttr('-', [
                'disabled' => true
            ])
                : FormUnit::radio('status', '状态')->options(HospitalsEnumStatus::getMap(true))->defaultValue(1)
            ,
//            FormUnit::text('audit_id', '审核人id')->options(Administrators::column('name', 'id')),
            FormUnit::hidden('operate_id', '申请人id')->defaultValue(0),
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
                FormUnit::text('i.name%%')->placeholder('医院名称'),
            ),
        ];

        return Form::create($form_data)->setSearchSubmitElement();
    }


}

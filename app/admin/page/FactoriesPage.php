<?php
/**
 * Factories.php
 * Date: 2022-07-05 09:56:06
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\common\enum\FactoriesEnumStatus;


/**
 * 厂商表
 * Class FactoriesPage
 * @package app\admin\page\FactoriesPage
 */
class FactoriesPage extends BasePage
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
            Column::normal('', 'id'),
            Column::normal('厂商名称', 'name'),
            Column::normal('地址', 'address'),
            Column::normal('经度', 'lng'),
            Column::normal('纬度', 'lat'),
            Column::normal('联系方式', 'phone'),
            Column::normal('状态', 'status'),
            Column::normal('', 'create_time'),
            Column::normal('', 'update_time'),
            Column::normal('', 'delete_time'),
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
            FormUnit::text('name', '厂商名称'),
            FormUnit::text('address', '地址'),
            FormUnit::text('phone', '联系方式'),
            FormUnit::radio('status', '状态')->options(FactoriesEnumStatus::getMap(true)),
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
                FormUnit::text('i.name%%')->placeholder('厂商名称'),
            ),
        ];
        
        return Form::create($form_data)->setSearchSubmitElement();
    }


}

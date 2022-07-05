<?php
/**
 * Varieties.php
 * Date: 2022-07-04 17:41:15
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;


/**
 * 品种表
 * Class VarietiesPage
 * @package app\admin\page\VarietiesPage
 */
class VarietiesPage extends BasePage
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
            Column::normal('名称', 'name'),
//            Column::normal('', 'create_time'),
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
            FormUnit::text('name', '名称'),
        ];

        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }




}

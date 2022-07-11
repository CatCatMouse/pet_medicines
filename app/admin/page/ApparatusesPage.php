<?php
/**
 * Apparatuses.php
 * Date: 2022-07-05 16:55:22
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\common\enum\ApparatusesEnumType;
use app\admin\model\Factories;
use app\common\model\Brands;
use app\common\enum\ApparatusesEnumStatus;


/**
 * 器械表
 * Class ApparatusesPage
 * @package app\admin\page\ApparatusesPage
 */
class ApparatusesPage extends BasePage
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
            Column::normal('缩略图', 'thumbnail')->showImage(),
            Column::normal('名称', 'name'),
            Column::normal('类型', 'type'),
            Column::normal('厂商id', 'factories_name'),
            Column::normal('品牌id', 'brands_name'),
            Column::normal('使用次数', 'times'),
            Column::normal('图片', 'imgs')->showImage(),
            Column::normal('状态', 'status'),
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
            FormUnit::image('thumbnail', '缩略图'),
            FormUnit::text('name', '名称'),
            FormUnit::radio('type', '类型')->options(ApparatusesEnumType::getMap(true)),
            FormUnit::select('factory_id', '厂商')->options(Factories::column('name', 'id')),
            FormUnit::select('brand_id', '品牌')->options(Brands::column('name', 'id')),
            FormUnit::text('times', '使用次数'),
            FormUnit::uEditor('desc', '介绍'),
            FormUnit::uEditor('indication', '适应症'),
            FormUnit::uEditor('contraindication', '禁忌症'),
            FormUnit::video('videos', '视频'),
            FormUnit::images('imgs', '图片'),
            FormUnit::radio('status', '状态')->options(ApparatusesEnumStatus::getMap(true)),
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
                FormUnit::select('i.type')->placeholder('类型')->options(ApparatusesEnumType::getMap(true)),
                FormUnit::select('i.factory_id')->options(Factories::column('name', 'id'))->placeholder('厂商'),
                FormUnit::select('i.brand_id')->options(Brands::column('name', 'id'))->placeholder('品牌'),
                FormUnit::text('i.name%%')->placeholder('名称'),
            ),
        ];
        
        return Form::create($form_data)->setSearchSubmitElement();
    }


}

<?php
/**
 * Cases.php
 * Date: 2022-07-05 17:50:27
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\admin\model\Hospitals;
use app\common\model\CaseTypes;
use app\common\model\CaseSubjects;
use app\common\model\Varieties;
use app\common\enum\CasesEnumSex;
use app\admin\model\User;


/**
 * 病例表
 * Class CasesPage
 * @package app\admin\page\CasesPage
 */
class CasesPage extends BasePage
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
            Column::normal('所属医院', 'hospitals_name'),
            Column::normal('名称', 'name'),
            Column::normal('病例分类', 'case_types_name'),
            Column::normal('病例科目', 'case_subjects_name'),
            Column::normal('品种', 'varieties_name'),
            Column::normal('年龄', 'age_year')->setFormat('{age_year}岁{age_month}个月'),
            Column::normal('性别', 'sex'),
            Column::normal('图片', 'imgs')->setTemplate('return custom.tableImageShow(obj.imgs.split(",")[0]);'),
            Column::normal('销售人', 'sale_name'),
            Column::normal('操作人', 'operate_name'),
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
            FormUnit::select('hospital_id', '所属医院')->options(Hospitals::column('name', 'id')),
            FormUnit::text('name', '名称'),
            FormUnit::select('case_type_id', '病例分类')->options(CaseTypes::column('name', 'id')),
            FormUnit::select('case_subject_id', '病例科目')->options(CaseSubjects::column('name', 'id')),
            FormUnit::select('variety_id', '品种')->options(Varieties::column('name', 'id')),
            FormUnit::text('age_year', '年龄年份'),
            FormUnit::text('age_month', '年龄月份'),
            FormUnit::radio('sex', '性别')->options(CasesEnumSex::getMap(true)),
            FormUnit::uEditor('desc', '描述'),
            FormUnit::uEditor('videos', '视频'),
            FormUnit::images('imgs', '图片'),
//            FormUnit::text('operate_id', '操作人id')->options(User::column('name', 'id')),
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
                FormUnit::select('i.hospital_id')->options(Hospitals::column('name', 'id'))->placeholder('所属医院'),
                FormUnit::select('i.case_type_id')->options(CaseTypes::column('name', 'id'))->placeholder('病例分类'),
                FormUnit::select('i.case_subject_id')->options(CaseSubjects::column('name', 'id'))->placeholder('病例科目'),
                FormUnit::select('i.variety_id')->options(Varieties::column('name', 'id'))->placeholder('品种'),
                FormUnit::text('i.name%%')->placeholder('名称'),
            ),
        ];
        
        return Form::create($form_data)->setSearchSubmitElement();
    }


}

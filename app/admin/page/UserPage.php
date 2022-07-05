<?php
/**
 * User.php
 * Date: 2022-07-05 09:33:02
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\common\enum\UserEnumType;
use app\common\enum\UserEnumStatus;


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
            Column::normal('上级', 'pid'),
            Column::normal('权限类型', 'type'),
            Column::normal('昵称', 'nickname'),
            Column::normal('姓名', 'name'),
            Column::normal('手机号', 'phone'),
            Column::normal('头像', 'avatar')->showImage(),
            Column::normal('账号', 'account'),
            Column::normal('身份证号', 'id_card'),
            Column::normal('微信openid', 'wx_openid'),
            Column::normal('邀请码', 'invite_code'),
            Column::normal('支付宝user_id', 'zfb_user_id'),
            Column::normal('会员等级', 'vip_level'),
            Column::normal('余额，分', 'balance'),
            Column::normal('积分', 'integral'),
            Column::normal('邮箱', 'email'),
            Column::normal('状态', 'status'),
            Column::normal('创建时间', 'create_time'),
            Column::normal('修改时间', 'update_time'),
            Column::normal('删除时间', 'delete_time'),
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
            FormUnit::select('pid', '上级'),
            FormUnit::select('type', '权限类型')->options(UserEnumType::getMap(true)),
            FormUnit::text('nickname', '昵称'),
            FormUnit::text('name', '姓名'),
            FormUnit::text('phone', '手机号'),
            FormUnit::password('password', '密码'),
            FormUnit::image('avatar', '头像'),
            FormUnit::text('account', '账号'),
            FormUnit::text('id_card', '身份证号'),
            FormUnit::text('wx_openid', '微信openid'),
            FormUnit::text('invite_code', '邀请码'),
            FormUnit::text('zfb_user_id', '支付宝user_id'),
            FormUnit::radio('vip_level', '会员等级'),
            FormUnit::text('balance', '余额，分'),
            FormUnit::text('integral', '积分'),
            FormUnit::text('email', '邮箱'),
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
                FormUnit::select('i.type')->placeholder('权限类型')->options(UserEnumType::getMap(true)),
                FormUnit::text('i.name%%')->placeholder('姓名'),
                FormUnit::text('i.phone%%')->placeholder('手机号'),
            ),
        ];
        
        return Form::create($form_data)->setSearchSubmitElement();
    }


}

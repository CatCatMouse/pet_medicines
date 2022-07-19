<?php
/**
 * Created by caicai
 * Date 2022/7/6 0006
 * Time 14:51
 * Desc
 */

use think\facade\Route;

/** 登录 */
Route::rule('login', 'Login/login', 'POST'); /** 登录 @see \app\api\controller\Login::login() */
/** 用户协议 */
Route::rule('userAgreements', 'Login/userAgreements', 'GET'); /** 用户隐私协议 @see \app\api\controller\Login::userAgreements() */
/** 微信相关信息同步 */
Route::rule('getUserInfo', 'User/getUserInfo', 'POST'); /** 获取用户信息 @see \app\api\controller\User::getUserInfo() */
Route::rule('updateUserWxInfo', 'User/updateUserInfo', 'POST'); /** 同步用户微信昵称和头像 @see \app\api\controller\User::updateUserInfo() */
Route::rule('updateUserWxPhone', 'User/updateUserPhone', 'POST'); /** 同步用户微信绑定的手机号 @see \app\api\controller\User::updateUserPhone() */

/** 首页 */
Route::rule('un_login_index', 'Index/un_login_index', 'POST'); /** 未登录的首页数据 @see \app\api\controller\Index::un_login_index() */
Route::rule('index', 'Index/index', 'POST'); /** 首页数据 @see \app\api\controller\Index::index() */


/** 器械 */
Route::rule('apparatus/lists', 'Apparatuses/lists', 'POST'); /** 器械列表 @see \app\api\controller\Apparatuses::lists() */
Route::rule('apparatus/detail', 'Apparatuses/detail', 'POST'); /** 器械详情 @see \app\api\controller\Apparatuses::detail() */

/** 病例 */
Route::rule('cases/lists', 'Cases/lists', 'POST'); /** 首页病例库列表 @see \app\api\controller\Cases::lists() */
Route::rule('cases/detail', 'Cases/detail', 'POST'); /** 病例详情 @see \app\api\controller\Cases::detail() */
Route::rule('cases/apparatus_cases', 'Cases/apparatus_cases', 'POST'); /** 器械相关病例列表 @see \app\api\controller\Cases::apparatus_cases() */
Route::rule('cases/add', 'Cases/add', 'POST'); /** 新增病例 @see \app\api\controller\Cases::add() */
Route::rule('cases/edit', 'Cases/edit', 'POST'); /** 编辑病例 @see \app\api\controller\Cases::edit() */

Route::group(function (){
    /** 手术crud */
    Route::rule('case_operation/add', 'CaseOperations/add', 'POST'); /** 新增手术 @see \app\api\controller\CaseOperations::add() */
    Route::rule('case_operation/edit', 'CaseOperations/edit', 'POST'); /** 编辑手术 @see \app\api\controller\CaseOperations::edit() */
    Route::rule('case_operation/before_add', 'CaseOperations/before_add', 'POST'); /** 新增术前 @see \app\api\controller\CaseOperations::before_add() */
    Route::rule('case_operation/middle_add', 'CaseOperations/middle_add', 'POST'); /** 新增术中 @see \app\api\controller\CaseOperations::middle_add() */
    Route::rule('case_operation/after_add', 'CaseOperations/after_add', 'POST'); /** 新增术后 @see \app\api\controller\CaseOperations::after_add() */

    Route::rule('case_operation/before_edit', 'CaseOperations/before_edit', 'POST'); /** 编辑术前 @see \app\api\controller\CaseOperations::before_edit() */
    Route::rule('case_operation/middle_edit', 'CaseOperations/middle_edit', 'POST'); /** 编辑术中 @see \app\api\controller\CaseOperations::middle_edit() */
    Route::rule('case_operation/after_edit', 'CaseOperations/after_edit', 'POST'); /** 编辑术后 @see \app\api\controller\CaseOperations::after_edit() */
})->middleware([\app\common\middleware\Token::class]);

/** 销售我的 */
Route::rule('sale/my_hospitals', 'User/bindHospitalLists', 'POST'); /** 医生详情 @see \app\api\controller\User::bindHospitalLists() */
Route::rule('sale/my_hospital_detail', 'User/bindHospitalDetail', 'POST'); /** 医生详情 @see \app\api\controller\User::bindHospitalDetail() */

/** 医院我的 */
Route::rule('doctor_apply_lists', 'User/doctorApplyLists', 'POST'); /** 医生审核列表 @see \app\api\controller\User::doctorApplyLists() */
Route::rule('doctor_apply_detail', 'User/doctorApplyDetail', 'POST'); /** 医生审核列表 @see \app\api\controller\User::doctorApplyDetail() */
Route::rule('doctor_apply_audit', 'User/auditDoctor', 'POST'); /** 医生审核列表 @see \app\api\controller\User::auditDoctor() */
Route::rule('doctor_lists', 'Hospital/doctorLists', 'POST'); /** 医生管理列表 @see \app\api\controller\Hospital::doctorLists() */
Route::rule('doctor_detail', 'Hospital/doctorDetail', 'POST'); /** 医生详情 @see \app\api\controller\Hospital::doctorDetail() */
Route::rule('doctor_edit', 'Hospital/doctorEdit', 'POST'); /** 医生详情 @see \app\api\controller\Hospital::doctorEdit() */
Route::rule('doctor_case_auth_edit', 'Hospital/changeCaseAuth', 'POST'); /** 医生病例操作权限编辑 @see \app\api\controller\Hospital::changeCaseAuth() */
Route::rule('doctor_revoke', 'Hospital/dockerRevoke', 'POST'); /** 撤销医生 @see \app\api\controller\Hospital::dockerRevoke() */

/** 游客我的 */
Route::rule('apply_doctor', 'User/doctorApply', 'POST'); /** 申请医生 @see \app\api\controller\User::doctorApply() */
Route::rule('apply_hospital', 'User/hospitalApply', 'POST'); /** 申请医院合作 @see \app\api\controller\User::hospitalApply() */

/** 通用 */
Route::rule('join_collection', 'User/joinCollection', 'POST'); /** 加入/取消收藏 @see \app\api\controller\User::joinCollection() */
Route::rule('my_collections', 'User/myCollections', 'POST'); /** 我的收藏 @see \app\api\controller\User::myCollections() */
Route::rule('types', 'CaseTypes/map_list', 'POST'); /** 分类 @see \app\api\controller\CaseTypes::map_list() */
Route::rule('subjects', 'CaseSubjects/map_list', 'POST'); /** 科目 @see \app\api\controller\CaseSubjects::map_list() */
Route::rule('varieties', 'Varieties/map_list', 'POST'); /** 科目 @see \app\api\controller\Varieties::map_list() */
Route::rule('hospitals', 'Hospital/map_list', 'POST'); /** 科目 @see \app\api\controller\Hospital::map_list() */

/** 医院申请页配置 */
Route::rule('doctor_apply_configs', 'User/doctorApplyConfig', 'POST'); /** 医生审核列表 @see \app\api\controller\User::doctorApplyConfig() */
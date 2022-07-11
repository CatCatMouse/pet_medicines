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
Route::rule('cases/apparatus_cases', 'Cases/apparatus_cases', 'POST'); /** 器械相关病例列表 @see \app\api\controller\Cases::apparatus_cases() */


/** 医院我的 */
Route::rule('doctor_apply_lists', 'User/doctorApplyLists', 'POST'); /** 医生审核列表 @see \app\api\controller\User::doctorApplyLists() */

/** 游客我的 */
Route::rule('apply_doctor', 'User/doctorApply', 'POST'); /** 申请医生 @see \app\api\controller\User::doctorApply() */
Route::rule('apply_hospital', 'User/hospitalApply', 'POST'); /** 申请医院合作 @see \app\api\controller\User::hospitalApply() */

/** 通用 */
Route::rule('my_collections', 'User/myCollections', 'POST'); /** 我的收藏 @see \app\api\controller\User::myCollections() */

/** 医院申请页配置 */
Route::rule('doctor_apply_configs', 'User/doctorApplyConfig', 'POST'); /** 医生审核列表 @see \app\api\controller\User::doctorApplyConfig() */
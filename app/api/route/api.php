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
/** 微信相关信息同步 */
Route::rule('getUserInfo', 'User/getUserInfo', 'POST'); /** 获取用户信息 @see \app\api\controller\User::getUserInfo() */
Route::rule('updateUserWxInfo', 'User/updateUserInfo', 'POST'); /** 同步用户微信昵称和头像 @see \app\api\controller\User::updateUserInfo() */
Route::rule('updateUserWxPhone', 'User/updateUserPhone', 'POST'); /** 同步用户微信绑定的手机号 @see \app\api\controller\User::updateUserPhone() */



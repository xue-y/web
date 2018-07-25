<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-19
 * Time: 下午1:09
 * 配置文件
 */
header("Content-type: text/html; charset=utf-8");
// 开发者ID(AppID)
$appid="";
//开发者密码(AppSecret)
$appsecret="";
// 回调地址 wx 端登录
//域名/wx   服务器 网页授权域名
//https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/function&action=function&token=1888472446&lang=zh_CN
$re_url=urlencode("http://域名/wx/wx_callback.php");
//
$state=123; // 自定义标识
$scope="snsapi_userinfo"; //应用授权作用域
//$scope="snsapi_base"; // 不弹出授权页面，直接跳转，只能获取用户openid
include "curl.php";
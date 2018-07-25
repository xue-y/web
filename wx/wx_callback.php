<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-20
 * Time: 上午8:50
 * 通过code换取网页授权access_token --回调地址
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842
 */
include "conf.php";

if(isset($_GET['code']) && isset($_GET["state"]))
{
    if($state!=$_GET["state"])
    {
        exit("state_error");
    }

    $code=$_GET['code'];
    $token_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
    $token_resturn=https_get($token_url);
    $token_data=json_decode($token_resturn,true);

    //如果请求 access_token 失败
    if(isset($token_data["errcode"]))
    {
        exit($token_data["errmsg"]);
    }

    $openid=$token_data["openid"];
    $web_access_token=$token_data["access_token"];
    $refresh_token=$token_data["refresh_token"];

    // 验证access_token 是否有效
    $refresh_url="https://api.weixin.qq.com/sns/auth?access_token=$web_access_token&openid=$openid";
    $refresh_info=https_get($refresh_url);
    $refresh_info=json_decode($refresh_info,true);
    // 如果access_token 过期
    if($refresh_info["errcode"]!==0)
    {
        //  刷新 access_token
        $refresh_access_token="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$appid&grant_type=refresh_token&refresh_token=$refresh_token";
        $refresh_resturn=https_get($refresh_access_token);
        $refresh_data=json_decode($refresh_resturn,true);
        // 如果刷新获取失败
        if(isset($refresh_data["errcode"]))
        {
            exit($refresh_data["errmsg"]);
        }
        // 如果成功再次赋值
        $web_access_token=$refresh_data["access_token"];
        $refresh_token=$refresh_data["refresh_token"];
    }

    // 拉取用户信息
    $user_url="https://api.weixin.qq.com/sns/userinfo?access_token=$web_access_token&openid=$openid&lang=zh_CN";
    $user_info=https_get($user_url);
    $user_info=json_decode($user_info,true);
    // 如果获取用户信息失败
    if(isset($user_info["errcode"]))
    {
        exit($user_info["errmsg"]);
    }

       /*$v=var_export($user_info,TRUE); // 获取用户信息写入数据库 测试
        file_put_contents("callback.txt",$v);*/
}
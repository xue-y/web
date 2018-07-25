<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-19
 * Time: 下午1:14
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140183
 * 获取开发者的 access_token
 */
    include "conf.php";

    // 获取 access_token
    if(isset($_COOKIE['access_token']))
    {
        $access_token=$_COOKIE['access_token'];
    }
    else
    {
        $token_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        $json_return=curl_url($token_url);
        $json_info = json_decode($json_return, true);
        $access_token =$json_info["access_token"];
        setcookie('access_token',$access_token,86400,'/');
    }


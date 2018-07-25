<?php
// 根据用户open id 获取用户信息
 include "token.php";
 /*
 $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
 $user_one=https_get($url);
 $user_one=json_decode($user_one);
//----------------------------------------------
*/
$url_list="https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=".$access_token;

$data_list='{
    "user_list": [
        {
            "openid": "用户openid", 
            "lang": "zh_CN"
        }, 
        {
            "openid": "用户openid", 
            "lang": "zh_CN"
        }
    ]
}';

$user_s=https_post($url_list,$data_list);
$user_s=json_decode($user_s);
var_dump($user_s);
<?php
// 获取用户openid
include "token.php";
 $re_url=urlencode("http://回调地址");

 $uu="https://open.weixin.qq.com/connect/qrconnect?appid=".$appid."&redirect_uri=".$re_url."&response_type=code&scope=snsapi_login&&state=12345#wechat_redirect";
 
 $code=https_get($uu);
 

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-20
 * Time: 上午11:19
 */
 include "conf.php";
 
 // 微信端授权登录---生成二维码

$code_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$re_url&response_type=code&scope=$scope&state=$state#wechat_redirect";

include 'phpqrcode.php';

$QR='qrcode.png';  // 二维码图片名称

$errorLevel = "L"; //定义纠错级别

$size = "4";  //定义生成内容

QRcode::png($code_url, $QR, $errorLevel, $size, 2);  // 执行生成图片

echo '<img src="'.$QR.'">';  //输出二维码

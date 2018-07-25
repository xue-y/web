<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-19
 * Time: 上午11:01
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842
 * 第一步 获取 code --- 此页面必须在微信端打开
 */

 include "conf.php";
//https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/function&action=function&token=111111&lang=zh_CN
//网页授权域名 域名/wx，不带 http://

$code_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$re_url&response_type=code&scope=$scope&state=$state#wechat_redirect";
header("Location:".$code_url);
exit;



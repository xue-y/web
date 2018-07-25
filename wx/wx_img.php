<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-19
 * Time: 下午12:54
 * 生成带参数的二维码 ----扫码进入进入公众号
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542
 */
function wx_img()
{
    include "wx_access_token.php";

    $qrcode = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 10000}}}';

    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
    $result =https_post($url,$qrcode);
    $jsoninfo = json_decode($result, true);
    $ticket = $jsoninfo["ticket"];

    $url_ticket="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
    echo  "<img src=".$url_ticket.">";
}
wx_img();
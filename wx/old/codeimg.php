<?php
 

     function get_erweimai()
	{
       include "token.php";

        //��ʱ
        $qrcode = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 10000}}}';
        //����
        // $qrcode = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 1000}}}';
         
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
        $result =https_post($url,$qrcode);
        $jsoninfo = json_decode($result, true);
        $ticket = $jsoninfo["ticket"];
  

        $url_ticket="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
        echo  "<img src=".$url_ticket.">";

	}

	 get_erweimai();
	
	
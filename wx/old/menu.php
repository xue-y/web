<?php
include "token.php";
$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
$data=' {
     "button":[
     {	
          "type":"click",
          "name":"今日歌曲",
          "key":"Song"
      },
      {
           "name":"菜单",
           "sub_button":[
           {	
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
               "type":"view",
               "name":"视频",
               "url":"http://v.qq.com/"
            },
            {
               "type":"click",
               "name":"赞一下",
               "key":"ok"
            }]
       }]
 }';
echo https_post($url, $data);
<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		
		// 急速数据的 key 值
		$jisuapi_key="";
		// 图灵机器人 key 值
		$tuling_key="";
      	
		if (!empty($postStr)){
                //通过simplexml 进行xml解析
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                //手机端
				$fromUsername = $postObj->FromUserName;
		     	//微信的公众平台
                $toUsername = $postObj->ToUserName;
				// 接受用户发送的关键词
                $keyword = trim($postObj->Content);
				
				$msgType=$postObj->MsgType;
				$event=$postObj->Event;
				$eventKey=$postObj->EventKey;		
                $time = time();
				
				$location_X=$postObj->Location_X;
				$location_y=$postObj->Location_Y;
				
				//文本模板
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
				//音乐模板			
				$musicTpl="<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Music>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<MusicUrl><![CDATA[%s]]></MusicUrl>
							<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							</Music>
							</xml>";
				//图文模板
				$newTpl="<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>%s</ArticleCount>
					        %s
							</xml> ";	

			if($msgType=='text')
			{       
				if(!empty( $keyword ))
                {
					if($keyword=='?' || $keyword=='？')
					{
						$msgType='text';
						$contentStr="[1]服务号码\n[2]通讯号码\n[3]银行号码\n您可以通过方括号中的编号获取您需要的内容";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}else if($keyword=='1')
					{
						$msgType='text';
						$contentStr="常用服务号码\n 火警：119\n 匪警：110";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					else if($keyword=='2')
					{
						$msgType='text';
						$contentStr="常用通讯号码\n 移动：10086\n 电信：10000";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					else if($keyword=='3')
					{
						$msgType='text';
						$contentStr="常用银行服务号码\n 工商银行：95588\n 建设银行：95533";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					else if($keyword=='音乐')
					{
						$msgType='music';
						$title='当爱已成往事';
						$desc="陈志朋版 《当爱已成往事》";
						$url="http://域名/music/m.mp3";
						$hurl="http://域名/music/m.mp3";
						$resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desc, $url, $hurl);
						echo $resultStr;
					}
					else if($keyword=='图文')
					{
						$msgType='news';
						$count=3;
						$str="<Articles>";
						for($i=0;$i<$count;$i++)
						{
						  $str.="<item>
								<Title><![CDATA[微信开发{$i}]]></Title> 
								<Description><![CDATA[第{$i}张图片]]></Description>
								<PicUrl><![CDATA[http://域名/music/ico-{$i}.gif]]></PicUrl>
								<Url><![CDATA[http://域名/zrgl/]]></Url>
								</item>";	
						}
						$str.="</Articles>";
						$resultStr = sprintf($newTpl, $fromUsername, $toUsername, $time, $msgType, $count, $str);
						echo $resultStr;
					}
					else 
					{
						 $msgType = "text";						
						 $url="http://www.tuling123.com/openapi/api?key=".$tuling_key."&userid=3456&info=".$keyword;
						 $str=file_get_contents($url);
						 $text=json_decode($str,true);
						 $contentStr = $text['text'];
					 	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
                }
				else{
                	echo "Input something...";
                }
			}else if($msgType=='image')
			{
					$msgType = "text";
                	$contentStr = "图片消息";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
			}
			else if($msgType=='location')
			{
				$msgType = "text";
				$url="http://api.jisuapi.com/geoconvert/coord2addr?lat=".$location_X."&lng=".$location_y."&type=baidu&appkey=".$jisuapi_key;
				$xin=file_get_contents($url);
				$xin=json_decode($xin);
				$weizhi=$xin->result->address.$xin->result->description;
				$contentStr = "地理：{$weizhi}";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}else if($msgType=='event')
			{
				switch($event)
				{
					case 'subscribe':
					$msgType = "text";
                	$contentStr = "欢迎关注测试账号，你好";
					file_put_contents('open_id.txt',$fromUsername."\r\n",FILE_APPEND);
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
				    break;
					case 'CLICK':
					$msgType = "text";
                	$contentStr = $eventKey;
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
				    break;
				}
					/**/
			}
			
        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}




?>
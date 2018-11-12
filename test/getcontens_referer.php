<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-24
 * Time: 上午10:21
 *  file_get_contents curl 伪造 网站来源地址
 */
/*$url = "http://localhost/test/test.php";
$refer="http://www.aa.com";
$opt=array('http'=>array('header'=>"Referer: $refer"));
//创建并返回一个资源流上下文，该资源流中包含了 options 中提前设定的所有参数的值。
//options 必须是一个二维关联数组，格式如下：$arr['wrapper']['option'] = $value 。默认是一个空数组
$context=stream_context_create($opt);
$file_contents = file_get_contents($url,false, $context);
echo $file_contents;*/

$url = "http://localhost/test/test.php";// 请求的页面地址
$refer="http://www.aa.com";  //伪造的页面地址
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL,$url);
curl_setopt ($ch, CURLOPT_REFERER,$refer);
curl_exec ($ch);
curl_close ($ch);

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-24
 * Time: 上午8:57
 * fsockopen 伪造 网站来源
 */

$url="http://localhost/test/test.php";
$target = "http://www.manongjc.com/";
/** sockopen 伪造 网站来源地址
 * @parem $url 要访问的页面地址
 * @parem $target 伪造来源页面
 * @parem $port 网站端口 默认 80
 * @parem 页面脚本执行时间 默认 30 s
 * */
set_time_limit(0);
function referer($url,$target, $port=80,$t=30)
{
    $info=parse_url($url);
    $fp = fsockopen($info["host"], $port, $errno, $errstr);
    if(!$fp)
    {
        echo "$errstr($errno)".PHP_EOL;
    }
    else
    {
        $out = "GET ".$info['path']." HTTP/1.1".PHP_EOL;
        $out .= "Host: ".$info["host"].PHP_EOL;
        $out .= "Referer: ".$target.PHP_EOL;
        $out .= "Connection: Close".PHP_EOL;
        $out .= PHP_EOL;
        fwrite($fp, $out);
        while(!feof($fp))
        {
          echo fgets($fp); // 发送 head 请求头信息
        }
        fclose($fp);
    }
}
//函数调用
referer($url,$target);
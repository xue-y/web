<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-23
 * Time: 下午1:49
 * fsockopen 连接 http 页面
 */
$url="http://www.manongjc.com";
$port=80;
$t=30;
/**fsockopen 抓取页面
 * @parem $url 网页地址  必须 http://xxxx
 * @parem $port 端口 默认 80
 * @parem $t 设置连接的时间 默认30s
 * */
function fsock($url, $port=80,$t=30)
{
    $info=parse_url($url);

    $fp = fsockopen($info['host'], $port, $errno, $errstr,$t);
    if (!$fp)
    {
        echo "$errstr ($errno)".PHP_EOL;
    }
    else
    {
        if(!isset($info['path']) || empty($info['path']))
        {
            $info['path']="/";
        }
        $out = "GET ".$info['path']." HTTP/1.1".PHP_EOL;
        $out .= "Host: ".$info['host'].PHP_EOL;
        $out .= "Connection: Close".PHP_EOL.PHP_EOL;
        fwrite($fp, $out);
        $content = '';
        while (!feof($fp))
        {
            $content .= fgets($fp);
        }
        echo $content;
        fclose($fp);
    }
}
// 函数调用
fsock($url, $port,$t);

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-23
 * Time: 下午2:43
 */
// 获取全部 HTTP 请求头信息---函数仅适用于 Apache 也可使用 别名 apache_request_headers()

$is_headers = function_exists('getallheaders');
$headers=array();
if(!isset($is_headers))
{
    foreach ($_SERVER as $key => $value)
    {
        if ('HTTP_' == substr($key, 0, 5)) {
            $headers[str_replace('_', '-', substr($key, 5))] = $value;
        }
        if (isset($_SERVER['PHP_AUTH_DIGEST']))
        {
            $header['AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST'];
        } elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            $header['AUTHORIZATION'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $header['CONTENT-LENGTH'] = $_SERVER['CONTENT_LENGTH'];
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $header['CONTENT-TYPE'] = $_SERVER['CONTENT_TYPE'];
        }
    }
}else
{
    $headers=getallheaders();
}
var_dump($headers);


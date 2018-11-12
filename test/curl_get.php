<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-22
 * Time: 下午5:17
 * curl :PHP开启curl库
 */

header("Content-type: text/html; charset=utf-8");
$url = 'https://www.baidu.com/';// 页面编码与php 执行页面编码一致
// 初始化 curl
$curl = curl_init();

// 设置URL和相应的选项 curl_setopt — 设置一个cURL传输选项。
//1.由 curl_init() 返回的 cURL 句柄; 2.需要设置的CURLOPT_XXX选项;3.将设置在option选项上的值
curl_setopt($curl, CURLOPT_URL, $url); // url
curl_setopt($curl, CURLOPT_HEADER, 1); // 将头文件的信息作为数据流输出； 1 为输出 ；0 不输出

//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出：
//1 或者 true为不输出，0 或false  直接输出到页面上
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);

// https请求 不验证证书和hosts 请求数据是使用，跳过验证； 例如参数 返回数据
//CURLOPT_SSL_VERIFYPEER 禁用后cURL将终止从服务端进行验证  默认为true
//如果CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

// 抓取URL并把它传递给浏览器 执行curl 返回页面数据
$data = curl_exec($curl);
// 关闭curl 连接
curl_close($curl);

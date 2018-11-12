<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-23
 * Time: 下午12:03
 * 官方文档 http://www.runoob.com/php/php-ref-curl.html
 */
header("Content-type: text/html; charset=utf-8");
$url = 'https://www.baidu.com/';// 页面编码与php 执行页面编码一致
/** curl  api 接口调用访问
 * @parem $url  接口地址
 * @parem $data 如果使用post 方式发送数据传递 $data 关联数组参数
 * @parem $cookie  设定HTTP请求中"Cookie: "部分的内容;多个cookie用分号分隔，分号后带一个空格(例如， "fruit=apple; colour=red")
 * */
function curl($url,$data=null,$cookie=null)
{
    // 初始化 curl
    $curl = curl_init();

    // 设置URL和相应的选项 curl_setopt — 设置一个cURL传输选项。
    //1.由 curl_init() 返回的 cURL 句柄; 2.需要设置的CURLOPT_XXX选项;3.将设置在option选项上的值
        curl_setopt($curl, CURLOPT_URL, $url); // url
        curl_setopt($curl, CURLOPT_HEADER, 0); // 将头文件的信息作为数据流输出； 1 为输出 ；0 不输出

    //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出：
    //1 或者 true为不输出，0 或false  直接输出到页面上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,0);

    // https请求 不验证证书和hosts 请求数据是使用，跳过验证； 例如参数 返回数据
    //CURLOPT_SSL_VERIFYPEER 禁用后cURL将终止从服务端进行验证  默认为true
    //如果CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    // 如果传送数据---- 使用post 方式
     if(isset($data) && !empty($data))
     {
        //设置post方式提交 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
         //表示是否启用第二个option，这里为CURLOPT_POST，设置为1，表示启用时会发送一个常规的POST请求
         curl_setopt($curl, CURLOPT_POST, 1);

         /*这个参数可以通过urlencoded后的字符串类似'para1=val1&para2=val2&...'或使用一个以字段名为键值，
         字段数据为值的数组。如果value是一个数组，Content-Type头将会被设置成multipart/form-data。
          * */
         curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
     }
    // 如果传递 HTTP请求中 的 Cookie
    if(isset($cookie) && !empty($cookie))
    {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }

    // 抓取URL并把它传递给浏览器 执行curl 返回页面数据
        $data = curl_exec($curl);
    // 关闭curl 连接
        curl_close($curl);
}
//  调用函数
curl($url);
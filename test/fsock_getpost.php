<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-23
 * Time: 下午1:49
 * get/post 请求
 */
$url = "http://localhost/test/test.php";
$port=80;
$t=30;
$data = array(
    'foo'=>'bar',
    'baz'=>'boom',
    'site'=>'www.manongjc.com',
    'name'=>'nowa magic');

/**fsockopen 抓取页面
 * @parem $url 网页地址 host 主机地址
 * @parem $port 端口 默认80
 * @parem $t 设置连接的时间 默认 30s
 * @parem $method 请求方式 get/post
 * @parem $data 如果单独传数据为 post 方式
 * @return 返回请求回的数据
 * */
function sock_data($url,$port='80',$t='30',$method='get',$data=null)
{
    $info=parse_url($url);
    $fp = fsockopen($info["host"],$port,$errno,$errstr,$t);

    // 判断是否有数据
    if(isset($data) && !empty($data))
    {
        $query = http_build_query($data); // 数组转url 字符串形式
    }else
    {
        $query=null;
    }
    // 如果用户的$url "http://www.manongjc.com/";  缺少 最后的反斜杠
    if(!isset($info['path']) || empty($info['path']))
    {
       $info['path']="/index.html";
    }
    // 判断 请求方式
    if($method=='post')
    {
        $head = "POST ".$info['path']." HTTP/1.0".PHP_EOL;
    }else
    {
        $head = "GET ".$info['path']."?".$query." HTTP/1.0".PHP_EOL;
    }

    $head .= "Host: ".$info['host'].PHP_EOL; // 请求主机地址
    $head .= "Referer: http://".$info['host'].$info['path'].PHP_EOL;

    if(isset($data) && !empty($data) && ($method=='post'))
    {
        $head .= "Content-type: application/x-www-form-urlencoded".PHP_EOL;
        $head .= "Content-Length: ".strlen(trim($query)).PHP_EOL;
        $head .= PHP_EOL;
        $head .= trim($query);
        // post 方式 使用network 查看时不显示  Referer Content-type Content-Length
        // 请求的方式显示的也是 get ,当时可以取到post 数据，原因不知？？？
    }else
    {
        $head .= PHP_EOL;
    }
    $write = fputs($fp, $head); //写入文件(可安全用于二进制文件)。 fputs() 函数是 fwrite() 函数的别名
    while (!feof($fp))
    {
        $line = fread($fp,4096);
        echo $line;
    }
}
// 函数调用
sock_data($url,80,30,'get',$data);



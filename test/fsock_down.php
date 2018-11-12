<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-2-23
 * Time: 下午4:06
 */

/* *
   PHP设置脚本最大执行时间的三种方法
    1、在php.ini里面设置
    max_execution_time = 120;
    2、通过PHP的ini_set函数设置
    ini_set("max_execution_time", "120");
    3、通过set_time_limit 函数设置
    set_time_limit(120);
    以上几个数字设置为0则无限制，脚本会一直执行下去，直到执行结束。
 * */
set_time_limit(0);

$url = 'http://jnmxb.cn/img.zip';
$port = '80';
/** sockopen 下载文件
 * @parem $url 访问文件的url 地址
 * @parem $port 默认 80
 * @parem $down_name 下载指定路径文件名称 例如 ../aa.zip
 * */
function sock_down($url,$port=80,$down_name=null)
{
    $info=parse_url($url);
    # 建立连接
    $fp = fsockopen($info["host"],$port,$errno,$errstr);
    /*
     为资源流设置阻塞或者阻塞模式  参数：资源流()，0是非阻塞，1是阻塞
    bool stream_set_blocking ( resource $stream , int $mode )
    阻塞的好处是，排除其它非正常因素，阻塞的是按顺序执行的同步的读取。将会一直等到从资源流里面获取到数据才能返回
    而非阻塞，因为不必等待内容，所以能异步的执行，现在读到读不到都没关系，执行读取操作后会立即返回数据
     * */
    stream_set_blocking($fp, 1);

    if(!$fp)
    {
        echo "$errno : $errstr<br/>";
    }
    else
    {
        # 发送一个HTTP请求信息头
        $request_header="GET ".$info['path']." HTTP/1.1".PHP_EOL;
        # 起始行
        # 头域
        $request_header.="Host: ".$info["host"].PHP_EOL;
        # 再一个回车换行表示头信息结束
        $request_header.=PHP_EOL;

        # 发送请求到服务器
        fputs($fp,$request_header);

        if(!isset($down_name) || empty($down_name))
        {
            $down_name=basename($url); //默认当前文件同目录
        }
        # 接受响应
        $fp2=fopen($down_name,'w'); // 要下载的文件名  下载到指定目录
        $line='';
        while (!feof($fp))
        {
            $line.= fputs($fp2,fgets($fp));
        }
        if(feof($fp))
        {
            echo "<script>alert('已下载到当前目录')</script>";
        }
        # 关闭
        fclose($fp2);
        fclose($fp);
    }
}
//函数调用
sock_down($url,$port,'2.zip');


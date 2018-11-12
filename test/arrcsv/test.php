<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-5
 * Time: 下午1:31
 *导出导入会员信息
 */

include 'Data.php';
$one = array ('a'=>'dsfsf','b'=>'中文','c'=>3,'d'=>4,'e'=>5);
$two=array(
    array("username"=>"汉字","password"=>"123"),
    array("username"=>"test2","password"=>"456"),
    array("username"=>"test3","password"=>"789"),
    array("username"=>"test4","password"=>"111"),
    array("username"=>"test5","password"=>"222"),
    array("username"=>"test6","password"=>"222"),
);
$config["fileName"]='中文two';
$data=new DataArr($config);
$data->readFile('中文one.csv');
/*$data->writeFile($two);*/
/*var_dump($two);*/

echo extension_loaded('mbstring');
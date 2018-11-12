<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-20
 * Time: 下午2:20
 */
namespace arrcsv;
use arrcsv\execData;
include "execData.php";


$one = array ('a'=>'dsfsf','b'=>'中文','c'=>3,'d'=>4,'e'=>5);
$two=array(
    array("username"=>"汉字","password"=>"123"),
    array("username"=>"test2","password"=>"456"),
    array("username"=>"test3","password"=>"789"),
    array("username"=>"test4","password"=>"111"),
    array("username"=>"test5","password"=>"222"),
    array("username"=>"test6","password"=>"222"),
);

//new ExecData();
//ExecData::writeData($two,'tit,标题,ccc');

ExecData::fetchData("中文two.zip",false,false,'中文one.csv',false);



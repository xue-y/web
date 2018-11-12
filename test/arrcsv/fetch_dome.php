<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-20
 * Time: 下午3:10
 */
namespace arrcsv;
use arrcsv\FetchFile;
include 'FetchFile.php';
$read=new FetchFile();

$read->fetchFile("中文two.zip",false,false,'中文one.csv',false);
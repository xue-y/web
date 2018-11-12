<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-11-9
 * Time: 下午1:28
 */
/*php 运行模式*/
/*echo php_sapi_name();
echo "<br/>";
echo PHP_SAPI;*/
/*Apache服务器的工作模式*/
//httpd -l 或者apache2 -l

// 如果需要添加小孩,就可以做添加一个小孩Hook::add("child");


class Hook{

     private  $hookList;

     //添加
   function add($name,$fun){
       $this->hookList[$name][] = $fun;
}

function excec($name){
   $value = func_get_args();
   unset($value[0]);
   foreach ($this->hookList[$name] as $key => $fun) {
       call_user_func_array($fun, $value);
   }
}



}
$hook = new Hook();

$hook->add('women',function($msg){
   echo 'oh my god'.$msg ;
});

$hook->add('man',function($msg){
   echo 'nothing'.$msg ;
});
// 执行
$hook->excec('man','taoge');
/*$hook->excec('women','aaa');*/


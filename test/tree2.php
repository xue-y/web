<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-15
 * Time: 上午9:56
 */

$array = array(
    array('id' => 1, 'pid' => 0, 'n' => '河北省'),
    array('id' => 2, 'pid' => 0, 'n' => '北京市'),
    array('id' => 3, 'pid' => 1, 'n' => '邯郸市'),
    array('id' => 4, 'pid' => 2, 'n' => '朝阳区'),
    array('id' => 5, 'pid' => 2, 'n' => '通州区'),
    array('id' => 6, 'pid' => 4, 'n' => '望京'),
    array('id' => 7, 'pid' => 4, 'n' => '酒仙桥'),
    array('id' => 8, 'pid' => 3, 'n' => '永年区'),
    array('id' => 9, 'pid' => 1, 'n' => '武安市'),
    array('id' => 10, 'pid' => 8, 'n' => '永年区镇')
);

function getTree($array, $pid =0, $level = 0){

    $f_name=__FUNCTION__; // 定义当前函数名

    // 空数组 不在执行
    if(empty($array))
        return;

    static $html;
    $html.="<ul>";
    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    foreach ($array as $key => $value){

        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            $flg = str_repeat('|--',$level);
            // 更新 名称值
            $value['n'] = $flg.$value['n'];
            // 输出 名称
            $html.="<li>".$value['n'];

            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            $f_name($array, $value['id'], $level+1);
            $html.="</li>\r\n";
        }else{
         // 删除数组，减少递归
         unset($array[$key]);
        }
    }
    $html.="</ul>\r\n";

    // 删除多余的 ul 标签
    $html=str_replace("<ul></ul>",'',$html);
    return $html;
}
// 调用
$list=getTree($array);
var_dump($list);

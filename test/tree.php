<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-9-18
 * Time: 上午10:32
 * 无限极分类
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


/** 所有的分类
 * @parem $array 数组
 * @parem $pid ，最高级别,默认为0，输出从pid 级别的数据
 * @parem $level 层级，默认0
 * */
function getTree($array, $pid =0, $level = 0){

    $f_name=__FUNCTION__; // 定义当前函数名
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
            //echo $value['n']."<br/>";
            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            $f_name($array, $value['id'], $level+1);
        }else
        {
           unset($array[$key]);
        }
    }
    return $list;
}
// 调用
$list=getTree($array);

/**根据指定id 查询，所有的子节点
 * @parem $id 要查询的id
 * @parem $array 查分类的数据，在项目使用中此参数可以不传，直接使用sql 查询
 * @parem $level 层级，默认1
 * */
function getSon($id,$array,$level=1)
{
    static $list;
    //$array=Db::table('table_name')->where('pid',$id)->select(); TP5
    foreach ($array as $k => $v)
    {
        if($v['pid'] == $id && $id>=0)
        {
            $flg = str_repeat('|--',$level);
            // 更新 名称值
            $v['n'] = $flg.$v['n'];
            // 输出 名称
             echo $v['n']."<br/>";
            //存放数组中
            $list[] = $v;
            unset($array[$k]);
            getSon($v['id'],$array,$level+1);
        }else
        {
            unset($array[$k]);
        }
    }
    return $list;
}
$list=getSon(1,$array);

/**根据指定id 的查询，所有的父节点
 * @parem $id_pid 要查询的id 或者 要查询id的pid；如果传入的是id 包括当前id 值，如果传入id_pid不包括当前id的值
 * @parem $array 查分类的数据，在项目使用中此参数可以不传，直接使用sql 查询
 * @parem $level 当前id所在层级，默认2
 * */
function getParent($id_pid,$array=array(), $level = 2)
{
    $f_name=__FUNCTION__; // 定义当前函数名
    static $list=array();
    //$array=Db::table('table_name')->where('id',$id_pid)->select(); TP5
    foreach($array as $k=>$v)
    {
        if($v['id']== $id_pid)
        {   //父级分类id等于所查找的id
            $flg = str_repeat('|--',$level);
            // 更新 名称值
            $v['n'] = $flg.$v['n'];
            // 输出 名称
           //  echo $v['n']."<br/>";
            $list[]=$v;
            unset($array[$k]);
            if($v['pid']>=0)
            {
                $f_name($v['pid'],$array,$level-1);
            }
        }else
        {
            unset($array[$k]);
        }
    }
    return $list;
}
// 调用
getParent(10,$array, $level = 3);
echo "<hr/>";
getParent(8,$array, $level = 3);
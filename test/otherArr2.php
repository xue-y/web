<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-8-15
 * Time: 上午9:46
 * 其他格式的数据 与 数组 互转
 * cvs数据中保留二维数组第一层循环的 key 值
 */

class otherArr {

    private $char="UTF-8";
    private $cvs_fege=","; // cvs 分割符

    /**数组 转 其他格式数据
     * @parem $data 要转换的数据
     * @parem $format xml json cvs
     * @return  string 如果没有数据传入返回 false
     * */
    public function  array_other($data,$format="json")
    {
        if(!is_array($data) || empty($data))
        {
            return false;
        }
        $format=strtolower($format);
        switch($format)
        {
            case "xml":
                $data2=$this->arr_xml($data);
                break;
            case "cvs":
                $data2=$this->arr_cvs($data);
                break;
            default:
                $data2=$this->arr_json($data);
                break;
        }
         return $data2;
    }

    /**其他格式数据 转 数组
     * @parem $data 要转换的数据
     * @parem $format 原数据格式
     * @return arr 如果没有数据传入返回 false
     * */
    public function other_array($data,$format)
    {
          if(!isset($data) || !isset($format) || empty($data) || empty($format))
          {
              return false;
          }
          $format=strtolower($format);
          switch($format)
          {
            case "xml":
                $data2=$this->xml_arr($data);
                break;
              case "cvs":
                  $data2=$this->cvs_arr($data);
                  break;
              case "json":
                  $data2=$this->json_arr($data);
                  break;
              default :
                  return $data;// 返回原数据
          }
          return $data2;

    }

    //------------------------------------------------------------数组转其他格式 start
    /** 数组 转 xml  * */
    private function arr_xml($data)
    {
        $xml = "<xml>";
        /* '<?xml version="1.0" encoding="UTF-8"?>'  */
        foreach ($data as $k=>$v)
        {
            if(is_array($v))
            {
                $xml.=is_numeric($k)?"":"<".$k.">";
                foreach ($v as $kk=>$vv)
                {
                    $xml.="<".$kk.">".$vv."</".$kk.">";
                }
                $xml.=is_numeric($k)?"":"</".$k.">";
            }else
            {
                $xml.="<".$k.">".$v."</".$k.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /** 一维 二维 数组 转 cvs  * */
    private function arr_cvs($data)
    {
        $string="";
        // 判断是一维数组还是二维数组  如果是一维数组
        if(count($data) == count($data,1))
        {
            $tit=array_keys($data);
            $string .= implode($this->cvs_fege,$tit)."\n";
        }else
        {
            $v_tit="";
        }

        foreach ($data as $k=> $v)
        {
            if(is_array($v)) // 二维数组
            {
                $string .= "\t'".$k."'\n";   //二维数组第一层循环的key 值分割符 \t

                if(empty($v_tit)) // 如果第二层循环中的key 值相同，只取一次值
                {
                    $v_tit=array_keys($v);
                    $string .= ",".implode($this->cvs_fege,$v_tit)."\n";
                }
                // 如果第二层循环中的key 值不相同，每次都取值
                /*$v_tit=array_keys($v);
                $string .= ",".implode($this->cvs_fege,$v_tit)."\n";*/

                $string .= ",".implode($this->cvs_fege,$v)."\n";
            }

        }
        // 一维数组
        if(count($data) == count($data,1))
        {
            $string .= implode($this->cvs_fege,$data)."\n";
        }
        return $this->char_gbk($string); // execle 打开cvs 不乱码
    }

    /** 数组 转 json * */
    private function arr_json($data)
    {
        foreach($data as $k=>$v)
        {
            if(is_array($v)) // 二维数组
            {
                foreach($v as $kk=>$vv)
                {
                    $v[$kk]=$this->char_utf($v[$kk]);
                }
            }else           //  一维数组
            {
                $data[$k]=$this->char_utf($data[$k]);
            }
        }
        return json_encode($data);
    }
    //------------------------------------------------------------数组转其他格式 end


    //------------------------------------------------------------其他格式转数组 start
    /** xml 转 数组 **/
    private function xml_arr($data)
    {
        //libxml_disable_entity_loader(true);禁止外部调用
        return json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /** json 转 数组 **/
    private function json_arr($data)
    {
        $data=$this->char_utf($data);
        return json_decode($data,true);
    }

    /** cvs 转 数组 **/
    private function cvs_arr($data)
    {
        $data=array_filter(explode("\t",$data));

        // 判断是一维还是二维数组
        if(isset($data[0]) && !empty($data[0])) // 二维数组 第一层开头分割使用的 \t，去除空值后下标从1 开始有值
        {
            $arr_type=1;
        }else
        {
            $arr_type=2;
            $data=array_values($data); // 重置数组下标
        }

        $new_data=array();
        $new_data2=array();
        if($arr_type==2)  // 二维数组
        {
            foreach($data as $k=>$v)  // 先转为二维数组
            {
                $data[$k]=array_filter(explode("\n",$v));

                foreach($data[$k] as $kk=>$vv)
                {
                    if($kk==0)          // 取得 二维数组第一层的 key
                    {
                        $vv=substr($vv,1,-1);
                        if(is_numeric($vv)) // 判断是不是索引数组
                        {
                            $vv=intval($vv);
                        }
                        $v=array_filter(explode("\n",$v));
                         array_shift($v);
                        $new_data[$vv]=$v;
                    }
                }
            }

           $new_data=array_filter($new_data);
            // 为二维数组赋 key 值
            $new_data2=array();
            $tit=array();
            foreach($new_data as $k=>$v)
            {
                if(count($v)>1) // 如果二维数组 第二次循环如果存在key 值取得 tit
                {
                    $tit=array_values(array_filter(explode($this->cvs_fege,$v[0])));
                    $v_arr=array_values(array_filter(explode($this->cvs_fege,$v[1])));
                }else
                {
                    $v_arr=array_values(array_filter(explode($this->cvs_fege,$v[0])));
                }
                foreach($tit as $kkk=>$vvv)
                {
                    $new_data2[$k][$tit[$kkk]]=$v_arr[$kkk];
                }
            }
        }else                                           //-----------一维数组
        {
            $new_data=explode("\n",$data[0]);
            $tit=explode($this->cvs_fege,$new_data[0]);
            $v_arr=explode($this->cvs_fege,$new_data[1]);
            foreach($tit as $k=>$v)
            {
                $new_data2[$tit[$k]]=$v_arr[$k];
            }
        }

        unset($data);
        return $new_data2;
    }
    //------------------------------------------------------------其他格式转数组 end

    /** 取得当前字符编码
     * @parem $str 要检验的字符
     * @parem  string 字符集
     * */
    private function get_character($str)
    {
        if(function_exists("mb_detect_encoding"))
        {
            return mb_detect_encoding($str);
        }else
        {
           if($this->is_gb2312($str))
           {
               return "GB2312";
           }else
           {
               return $this->char;
           }
        }
    }

    /** 判断是gbk还是utf-8 只应用于中文
     * @parem $str 要检验的字符
     * @return  bool: true - 含GB编码 false - 为UTF-8编码
     * */
    private function is_gb2312($str)
    {
        for($i=0; $i<strlen($str); $i++)
        {
            $v = ord( $str[$i] );
            if( $v > 127)
            {
                if( ($v >= 228) && ($v <= 233) )
                {
                    if( ($i+2) >= (strlen($str) - 1)) return true;  // not enough characters
                    $v1 = ord( $str[$i+1] );
                    $v2 = ord( $str[$i+2] );
                    if( ($v1 >= 128) && ($v1 <=191) && ($v2 >=128) && ($v2 <= 191) ) // utf编码
                        return false;   // utf-8
                    else
                        return true;  // gbk
                }
            }
        }
        return true; // gb2312
    }

    /**转换字符 其他字符转utf8
     * */
    private function char_utf($str)
    {
        $character=$this->get_character($str);
        if($character==$this->char)
        {
            return $str;
        }
        if(function_exists('mb_convert_encoding'))
        {
            $str=mb_convert_encoding($str, $this->char,$character);
        }else if(function_exists('iconv'))
        {
            $str2=iconv($character,$this->char."//IGNORE", $str);
            if(!empty($str2))// 如果字符不能以目标字符集表达的字符将被默默丢弃 防止字符为空
            {
                return $str2;
            }
        }
        // 如果没有转换字符函数 直接返回字符
        return $str;
    }

    /** utf 转换 gbk
     * */
    private function char_gbk($str)
    {
        $char=$this->is_gb2312($str);
        if($char)
            return $str;
        if(function_exists('mb_convert_encoding'))
        {
            $str=mb_convert_encoding($str,"GBK",$this->char);
        }else if(function_exists('iconv'))
        {
            $str2=iconv($this->char,"GBK//IGNORE", $str);
            if(!empty($str2))// 如果字符不能以目标字符集表达的字符将被默默丢弃 防止字符为空
            {
                return $str2;
            }
        }
        return $str;
    }

}

// 调用示例 如果下载数据可以用excel 打开
$arr = array ('a'=>'dsfsf','b'=>2,'c'=>3,'d'=>4,'e'=>5);
$data=array(
    "a"=>array("username"=>"汉字","password"=>"123"),
    "b"=>array("username"=>"test2","password"=>"456"),
    "c"=>array("username"=>"test3","password"=>"789"),
);
$data2=array(
    array("username"=>"test1","password"=>"123"),
    array("username"=>"test2","password"=>"456"),
    array("username"=>"test3","password"=>"789"),
    array("username"=>"test4","password"=>"111"),
    array("username"=>"test5","password"=>"222"),
);
$otherArr=new otherArr();
$f_data=$otherArr->array_other($data,"cvs");
var_dump($f_data);
echo "<hr/>";
$f_data2=$otherArr->other_array($f_data,"cvs");
var_dump($f_data2);

// 下载 cvs  文件
/*$filename = date('Ymd').'.csv'; //设置文件名
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
header('Expires:0');
header('Pragma:public');
echo $f_data;*/
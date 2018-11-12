<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-8-15
 * Time: 上午9:46
 * 其他格式的数据 与 数组 互转
 *  cvs 转换中不保留 二维数组第一层循环的 key 值，第二层循环的key为一致的
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
     * @parem $tit_true 二维数组第二层key 值是否相同，默认相同 为true
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
                if(empty($v_tit)) // 如果第二层循环中的key 值相同，只取一次值
                {
                    $v_tit=array_keys($v);
                    $string .= implode($this->cvs_fege,$v_tit)."\n";
                }
                $string .= implode($this->cvs_fege,$v)."\n";
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

    /** cvs 转 数组   返回二维数组 **/
    private function cvs_arr($data)
    {
        $data=array_values(array_filter(explode("\n",$data)));
        foreach($data as $k=>$v)
        {
            if($k<1)
            {
                $tit=explode($this->cvs_fege,$v);
            }else
            {
                $v_arr=array_values(array_filter(explode($this->cvs_fege,$v)));
                foreach($tit as $kkk=>$vvv)
                {
                    $new_data[$k-1][$tit[$kkk]]=$v_arr[$kkk];
                }
            }
        }
        unset($data);
        return $new_data;
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
    array("username"=>"汉字","password"=>"123"),
    array("username"=>"test2","password"=>"456"),
    array("username"=>"test3","password"=>"789"),
    array("username"=>"test4","password"=>"111"),
    array("username"=>"test5","password"=>"222"),
);
$otherArr=new otherArr();
$f_data=$otherArr->array_other($data2,"cvs");
/*var_dump($f_data);
echo "<hr/>";
$f_data2=$otherArr->other_array($f_data,"cvs");*/
//var_dump($f_data2);

// 下载 cvs  文件
$filename = date('Ymd').'.csv'; //设置文件名
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
header('Expires:0');
header('Pragma:public');
echo $f_data;
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-18
 * Time: 上午10:25
 * 数组数据导入导出
 */
class DataArr {

    private $config=[
        'isCompress'    => false, // 导出的如果是单个文件是否压缩  默认不压缩
        'webChar'       => 'UTF-8', // 文件、网页编码
        'fileNameChar'  =>'GBK//IGNORE', // 文件名编码 支持中文 GBK//IGNORE
        'localTime'     => 'PRC', // 地区时间
        'limit'          => 30,         // 写入数据条数
        'importFileMax' => 5,           // 导入文件大小 5MB 5*1024*1024
        'fileDir'        => './file/',  // 文件夹名不得为中 . / 英文 数组 下划线
        'logDir'         => './log/',    // 文件夹名不得为中 . / 英文 数组 下划线
        'logFile'        => 'error.txt',    // 日志文件名
        'fileName'       =>"",  // 写入文件时的文件名，只要文件名，不需要后缀名
    ];

    // 不对用户 开放的配置信息
    private $deconfig=[
        'minLimit'      => 1,       // 写入文件最少数据条数
        'maxLimit'      => 300,         // 写入文件最多数据条数
        'fileExt'        =>'.csv',  // 写入文件格式、压缩包中文件的格式
    ];

    // 导入文件格式
    private $mimeType=[
        'csv' => 'text/plain',
        'zip' => 'application/zip'
    ];

    // 写入文件
    private $arrData;     //  数组数据
    private $arrDataTit;  // 数据标题
    private $arrConut;    // 数组维度
    private $csvBr=PHP_EOL;  // csv 换行符
    private $csvLimiter=',';    // 字段分割符
    private $arrC=0;   // 数组个数

    public function __construct($config=array())
    {
        $this->config=array_merge($this->config,$config);
        header("Content-type: text/html; charset=".$this->config['webChar']);
        $this->config['fileDir']=$this->mkFileDir($this->config['fileDir']);
    }

    /** 读取文件
     * @parem $filename 要读取的文件名
     * @parem int 读取第几个文件的数据，默认0 读取所有文件，如果压缩文件中只有一个文件忽略此参数
     * 如果 $index=1,读取第一个文件
     * @parem bool 是否返回文件中的tit，默认false 不返回
     * @parem bool 是否将 csv 文件中的tit 做为数组的 key ,默认false 返回索引数组
     * */
    public function readFile($filename,$index=0,$tit=false,$key=true)
    {
        // 拼接文件名称 文件编码统一
         $filename=$this->config['fileDir'].$this->fileNameCode($filename);
        // 判断文件是否存在
        if(!file_exists($filename))
        {
            // 输出的文件名转换编码 -- 与页面一致
            $filename=$this->outFileName($filename);
            exit($filename.'文件不存在');
        }

        // 文件大小
        if(filesize($filename)> $this->config['importFileMax']*1024*1024)
        {
            exit('文件过大，建议文件小于5MB,也可修改 importFileMax 属性（根据自己的内存设定）');
        }

        // 判断文件格式
        $file_ext=$this->fileType($filename);

        if($file_ext=='csv')
        {
            $this->csvArr($filename);
        }
        // 每个文件第一组数据为 tit
        //csv 文件直接读取
        //zip 解压是读取数据
        //返回数组
    }

    /** arr 数组写入文件
     * */
    public function writeFile($arr,$tit=null)
    {
        // 时区时间
        date_default_timezone_set($this->config['localTime']);

        // 日志
        $this->config['logDir']=$this->mkFileDir($this->config['logDir']);
        $this->config['logFile']=$this->config['logDir'].$this->config['logFile'];

        $this->fileName();// 编码后的文件名或默认文件名

        // 初始数据
        $this->arrData=$arr;
        $this->arrDataTit=$tit;  //如果为空取数组的下标[0]的kye ,如果传值使用数组下标[0]长度与 tit 数组长度一致

       // 判断数组维度
        $this->isArrConut();

        // 写入文件
        $file_name=$this->writeFileSub();

        // 判断文件是否创建成功
        $this->isFile($file_name);
    }

    /**判断用户是否下载完成或取消下载 删除本地文件
     * @pream 需要下载的文件名 全路径
     * @return void
     * */
    private function exctDownFile($filename)
    {
        $fp=fopen($filename,"r");
        $file_ext=pathinfo($filename,PATHINFO_EXTENSION);
        header("Content-type:application/".$file_ext);

        $f_size=filesize($filename);
        header("Accept-Ranges:bytes");
        header("Accept-Length:".$f_size);

        $f_arr=explode("/",$filename);
        $new_file_name=end($f_arr);
        header("Content-Disposition:attachment;filename=".$new_file_name);
        header("Content-Transfer-Encoding:binary");

        $buffer=1024; //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器）
        $file_count=0; //读取的总字节数
        //向浏览器返回数据
        while(!feof($fp) && $file_count<$f_size){
            $file_con=fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_con;
        }
        fclose($fp);
        //下载完成后删除压缩包，临时文件夹
        if($file_count >= $f_size)
        {
            $this->unFile($filename);
        }
    }


    /** 数组数据写入文件
     * @return  filename
     */
    private function writeFileSub()
    {
        // 创建压缩包
        $zip_file_name=$this->config['fileDir'].$this->config["fileName"].'.zip';
        $zip=new ZipArchive();
        if(!$zip->open($zip_file_name,ZipArchive::OVERWRITE))
        {
            exit("创建压缩包失败");
        }
        $this->arrLimit();      // 数据条数

        if($this->arrC> $this->config['limit'])
        {
            $data=$this->arrData; // 所有数据
            $new_c=$this->arrC;
            $page=0;
            $star=0;
            while($new_c>0)
            {
                $new_data=array_slice($data,$star,$this->config['limit']);
                $file_data=$this->arrCsv($new_data);
                $page++;
                $star=$this->config['limit']*$page;
                $new_c-=$this->config['limit'];
                // 直接将数据写入，不占用磁盘空间，同时压缩完文件后不要删除源文件
                $zip->addFromString($this->config["fileName"].'_'.$page.$this->deconfig["fileExt"],$file_data);
                ob_clean();
            }
            $zip->close();
            return $zip_file_name;
        }

        // 取得单个文件数据
        $file_data=$this->arrCsv();

        // 是不是压缩----- 压缩
        if($this->config['isCompress']==true)
        {
            $new_file_name=$this->config["fileName"].$this->deconfig["fileExt"];
            $zip->addFromString($new_file_name,$file_data);
            $zip->close();
            ob_clean();
            return $zip_file_name;
        }
        // 如果不压缩--- 单个文件
        $new_file_name=$this->config['fileDir'].$this->config["fileName"].$this->deconfig["fileExt"];
        file_put_contents($new_file_name,$file_data);
        ob_clean();  //---------------------------------写入完成后，清理缓冲区
        return $new_file_name;
    }

    /** 数组转换csv格式
     * @return 单个文件带格式的csv数据
     * */
    private function arrCsv($arrdata=array())
    {
        if(empty($arrdata))
        {
            // 单卷文件不用传入数据，直接使用初始数据
            $file_data=$this->arrData;
        }else
        {
            // 多卷文件传入分割数组的数据
            $file_data=$arrdata;
        }

        // 一维
        if($this->arrConut==true)
        {
            $data=$this->csvBr;
            $head=chr(0xEF).chr(0xBB).chr(0xBF); // 防止 excle 打开乱码
            if(empty($this->arrDataTit))
            {
                $tit=array_keys($file_data);
                $head.=$tit[0];
            }
            $data.=implode($this->csvBr,$file_data);
        }else
        {
            // 二维
            if(empty($this->arrDataTit))
            {
                $tit=array_keys($file_data[0]);
            }else
            {
                if(count($this->arrDataTit)!=count($file_data[0]))
                {
                    exit("tit 数组长度与数据每个二维数据长度不一致");
                }
            }
            $data='';
            $head=chr(0xEF).chr(0xBB).chr(0xBF); // 防止 excle 打开乱码
            $head.=implode($this->csvLimiter,$tit);
            foreach($file_data as $v)
            {
                $data.=$this->csvBr;
                $data.=implode($this->csvLimiter,$v);
            }
        }
         return   $head.$data;
    }

    /** 文件编码处理
     * @parem $filename 文件名 str or arr
     * @return filename  str or arr
    */
    private function fileNameCode($filename)
    {
        if(!is_array($filename))
        {
            if(strlen($filename)!=mb_strlen($filename,$this->config['webChar']))
            {
                $filename=@iconv($this->config['webChar'],$this->config['fileNameChar'],$filename);
            }
            // 无法转换的文件名 使用 时间命名
            if(empty($filename))
                $filename=$this->deFileName();

            return $filename;
        }
        foreach($filename as $v)
        {
            $new_file_name[]=$this->fileNameCode($v);
        }
        return $new_file_name;
    }

    /** 判断用户是否传入文件名
     * @return void文件名的编码处理后或默认文件名
     * */
    private function fileName()
    {
        if(empty($this->config["fileName"]))
        {
           $this->config["fileName"]=$this->deFileName();
        }else
        {
            $this->config["fileName"]=$this->fileNameCode($this->config["fileName"]);
        }
    }

    /** 默认文件名处理
     * 用户不传文件名，或 iconv 处理后为空
     * @return filename
     * */
    private function deFileName()
    {
        return date('Y_m_d_his',time());
    }

    /** 文件写入完成判断文件是否正常并且存在自己下载
     * */
    private function isFile($filename)
    {
         if(!file_exists($filename))
         {
             exit($filename.'文件创建失败');
         }
        // 下载文件
        $this->exctDownFile($filename);
    }

    /** 输出的文件名：文件名转为与网页一样的格式
     * 如果是英文或数字此步骤可以忽略
     * */
    private function outFileName($filename)
    {
        if(strlen($filename)!=mb_strlen($filename,$this->config['webChar']))
        {
            $fn_arr=explode('/',$filename);
            $fn=array_pop($fn_arr);

            $fn=iconv($this->config['fileNameChar'],$this->config['webChar'],$fn);
            $filename=implode('/',$fn_arr).'/'.$fn;
        }
        return $filename;
    }

    /* 文件数据条数 */
    private function arrLimit()
    {
        $this->config['limit']=max($this->deconfig['minLimit'],$this->config['limit']);
        $this->config['limit']=min($this->deconfig['maxLimit'],$this->config['limit']);
    }

    /** 判断数组维度 赋值数组总条数
     * @return  void数组维度true一维false二维
    */
    private function isArrConut()
    {
        $this->arrC=count($this->arrData);
        if($this->arrC ==count($this->arrData,1))
        {
            $this->arrConut=true;
        }else
        {
            $this->arrConut=false;
        }
    }

    /**创建文件目录
     * @parem $filedir
     * @return 目录名称
     */
    private function mkFileDir($filedir)
    {
        $preg="/^[a-z0-9\.\/\-\_]+$/";

        if(empty($filedir) || (!preg_match($preg,$filedir)))
        {
            exit("请传入目录名,不得有特殊字符或中文");
        }
        if(!is_dir($filedir) && !@mkdir($filedir,0777))
        {
            exit($filedir.'目录创建失败');
        }
        if(is_dir($filedir) && !is_writable($filedir))
        {
            exit($filedir.'目录不可写');
        }

        // 判断目录名是否有最后面的  /
        if(substr($filedir,-1,1)!='/')
        {
            $filedir.'/';
        }
        return $filedir;
    }

    /** 删除压缩后的原文件
     * @parem $filename str 单个文件 arr 多个文件
     * 失败写入日志，成功返回true
     * */
    private function unFile($filename)
    {
        if(is_array($filename))
        {
            foreach ($filename as $v)
            {
                $this->unFile($v);
            }
        }else
        {
            if(!@unlink($filename))
            {
                $this->log($filename.'删除失败');
            }
        }
    }

    /** 导入文件 -- 判断文件格式
     * @parem $filename
     * @return file_ext
     * */
    private function fileType($filename)
    {
        if(!extension_loaded('fileinfo'))
        {
            exit("请开启php fileinfo 扩展");
        }
        $handle=finfo_open(FILEINFO_MIME_TYPE);//This function opens a magic database and returns its resource.
        $fileInfo=finfo_file($handle,$filename);// Return information about a file
        finfo_close($handle);

        $old_file_ext=strtolower(pathinfo($filename,PATHINFO_EXTENSION));
       if(!isset($this->mimeType[$old_file_ext]) || ($this->mimeType[$old_file_ext]!=$fileInfo))
        {
            $filename=$this->outFileName($filename);
            $file_ext=implode('|',array_keys($this->mimeType));
            exit($filename."不是标准的".$file_ext.'文件');
        }
        return $old_file_ext;
    }

    /** csv 文件返回arr 数组
     * @parem  $filename csv 文件名
     * @return csv文件data arr
     * */
    private function csvArr($filename)
    {
        $file = new SplFileObject($filename);
        $tit=$file->fgetcsv();
        $file->fseek(count($tit[0])-1,SEEK_CUR);
         // 一维
        if(count($tit)==1)
        {
            $this->arrConut=true;
            while (!$file->eof()) {
                $arr[]=$file->fgetcsv();
            }
        }else
        {
            //二维数组
            $this->arrConut=false;
            while (!$file->eof()) {
                $arr[]=$file->fgetcsv();
            }
        }
        /* 转换变量 编码为内部（internal）编码 */
        mb_internal_encoding("UTF-8");
        return $arr;
    }

    /** csv 数据转码 中文字符转utf-8
     * */
    private function dataCode()
    {

    }

    /** 警告信息写入日志
     * @parem $message 需要写入的log 日志信息
     * */
    private function log($message)
    {
        $file_info=pathinfo($this->config["logFile"]);
        $this->mkdirFile($file_info["dirname"]);

        $type="[Notice] ";
        $data=date($this->config['logTimeFormat']);
        $br=PHP_EOL;
        $info=$type.$data.' [Message]：'.$message.$br;

        error_log($info,3,$this->config["logFile"]);
    }


}
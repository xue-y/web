<?php
/**调用类
 */
namespace arrcsv;
use arrcsv\WriteFile;


include 'Pub.php';
include 'WriteFile.php';
include 'FetchFile.php';

class ExecData {

    /* 公共配置 */
    static private  $config=[
        'webChar'       => 'UTF-8', // 文件、网页编码
        //'fileNameChar'  =>'GBK//IGNORE', // 文件名编码 支持中文 GBK//IGNORE
        'fileNameChar'  =>'GBK',
        'localTime'     => 'PRC', // 地区时间
        'fileDir'        => './file/',  // 文件存在目录，文件夹名不得为中 . / 英文 数组 下划线
    ];

    /**数组数据导出
     * @parem $data 数组数据
     * @parem $tit csv 文件tit,arr 或者 'aa,bb' 字符串类型,建议数组长度与数据每个二维数据长度一致
     * @parem $limit 每个文件数据条数
     * @parem $filename 文件名，不加后缀，例如 aa,生成的文件是 aa.csv /aa.zip；默认文件名 WriteFile->deFileName()函数定义
     * @parem $compr 单个文件是否压缩，默认false 不压缩
     * @return 直接下载文件
     * */
    static public function writeData($data,$tit=null,$limit=100,$filename='',$compr=false)
    {
          $w_config=[
            'limit'          => intval($limit),         // 写入数据条数
            'fileName'       => $filename,  // 写入文件时的文件名，只要文件名，不需要后缀名
            'isCompress'    => $compr, // 导出的如果是单个文件是否压缩  默认不压缩
          ];
          $new_conf=array_merge(self::$config,$w_config);
          $write_file=new WriteFile($new_conf);
          $write_file->writeFile($data,$tit);
    }

    /*csv、zip 文件读取返回arr 数据
     * @parem $filename 要读取的文件名
     * @parem bool 是否返回文件中的tit，默认false 不返回;
     * @parem bool 是否将 csv 文件中的tit 做为数组的 key ,默认false 返回索引数组
     * @parem int/string
     * int读取第几个文件的数据，默认0 读取所有文件，如果压缩文件中只有一个文件忽略此参数
     * 如果 $index=1,读取第一个文件;
     * string 要读取的文件名如果嵌套文件 请添加文件夹路径 例如 aa/aa.csv,$fileway 参数必须为 false
     * @parem bool 读取压缩包中文件的方式 默认索引,索引方式读取ture,文件名读取文件false ,此参数仅对压缩文件起作用
     * @return array 返回文件数组数据
     * */
    static public function fetchData($filename,$tit=false,$key=false,$iden=0,$fileway=true)
    {
         $f_config=[
            'logFile'        => './log/error.txt',    // 文件夹名不得为中 . / 英文 数组 下划线日志文件名
            'logTimeFormat' => 'Y:m:d H:i:s',  // 日志时间格式
            'importFileMax' => 5,           // 导入文件大小 5MB 5*1024*1024
         ];
        $new_conf=array_merge(self::$config,$f_config);
        $fetch_file=new FetchFile($new_conf);
        $fetch_file->fetchFile($filename,$tit,$key,$iden,$fileway);
    }
} 
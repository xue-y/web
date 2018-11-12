<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-14
 * Time: 下午1:55
 * 压缩解压类
 */

class Compress {

    private $file_dir='./file';
	//PharData 与 Phar
    //tar
    // 压缩tar
    public  function tarPress()
    {

         $file_name=$this->file_dir."/1.txt";
         $bzip_name=$this->file_dir.'/tar.tar.gz';

         file_put_contents($file_name,'aaaa');
        file_put_contents($file_name,'bbb');
        try {
            $a = new PharData($bzip_name); // 如果同名存在自动覆盖掉
            $a->addFile($file_name,'1.txt');

            var_dump(pathinfo($bzip_name));

        } catch (Exception $e) {

            var_dump($e->getMessage());
        }

    }

    //tar 解压
    public function rarDivide()
    {
        try {
            $bzip_name=$this->file_dir.'/tar.tar';
          //  echo $bzip_name;
            $phar = new PharData($bzip_name);
          //  $phar->extractTo('/full/path'); // extract all files
         $phar->extractTo('file', '1.txt'); // extract only file.txt


        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }





}
$yasu=new Compress();
$yasu->tarPress();

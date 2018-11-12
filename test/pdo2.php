<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-10-7
 * Time: 下午3:37
 */
class Object {

    /**
     * 基本配置信息
     * @var array
     */
    private $config = array(
        'dbms'=>    'mysql',    //数据库类型
        'host'=>    'localhost',//数据库主机名
        'port'=>    3306,   //数据库端口
        'dbName'=>  'back',    //使用的数据库
        'user'=>    'root',     //数据库连接用户名
        'pass'=>    '',          //对应的密码
        'char'=>    'utf8',  // 字符集
        'long_conn'=>false, // 是否是长连接
    );
    // 数据连接 dsn
    private $dsn="";

    // 定义私有属性
    private static $_instance = null;

   // 定义 静态 pdo 在实例化的时候也可以使用静态调用
   // private static $pdo=null;
    private $pdo=null;

    //初始化
    private function __construct()
    {
        // 拼接dsn
        $this->str_dsn();
    }

    //公共化获取实例方法
    public static function getInstance(){
        //if (!(self::$_instance instanceof Object)) // 这个两种方式都可以
        if (self::$_instance === null)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    //私有化克隆方法
    private function __clone(){

    }
    /**
     * 使用 $this->name 获取配置
     * @param  string $name 配置名称
     * @return multitype    配置值
     */
    public function __get($name)
    {
        return $this->config[$name];
    }

    public function __set($name,$value)
    {
        if(isset($this->config[$name]))
        {
            $this->config[$name] = $value;
        }
    }

    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    // 拼接dsn 连接字符串
    private function str_dsn()
    {
        return  $this->dsn="$this->dbms:host=$this->host;port=$this->port;dbname=$this->dbName;charset=$this->char";
    }

    // pdo 连接
    public  function conn()
    {
        if($this->config['long_conn']==true)
        {
            $this->config['long_conn']=array(PDO::ATTR_PERSISTENT => true);
        }else
        {
            $this->config['long_conn']=array();
        }
        try {
            // 实例化 PDO 对象
            $pdo = new PDO($this->dsn, $this->config['user'], $this->config['pass'],$this->config['long_conn']);

            echo '对象：';
            var_dump($pdo);
            echo "<br/>";
            echo '参数 user 值: '.$this->user;

            // 如果使用静态pdo 可以使用下面这种方法
            /*if(self::$pdo === null)
            {
                self::$pdo = new PDO($this->dsn, $this->config['user'], $this->config['pass'],$this->config['long_conn']);
            }
            return self::$pdo;*/

        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    // 释放对象、销毁对象
    public function __destruct()
    {
        //使用静态方法时调用
        /*if(self::$pdo !== null)
            self::$pdo = null;*/
    }
}
$singleton=Object::getInstance();
$singleton->pass="admin";
$singleton->conn();
echo "<br/>";
$singleton->conn();
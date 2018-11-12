<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-9-23
 * Time: 下午3:31
 * pdo 操作函数 ，项目中使用注意命名空间
 */
class Object {

    /**
     * 基本配置信息
     * @var array
     */
    private $config = array(
        'dbms'=>    'mysql',    //数据库类型
        'host'=>    'localhost',//数据库主机名
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

    /** 初始化对象
     * @parem $config  array
     * */
    private function __construct()
    {
        // dsn 字符串
        $this->str_dsn();
    }

    //公有化获取实例方法
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
        return  $this->dsn="$this->dbms:host=$this->host;dbname=$this->dbName;charset=$this->char";
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

        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
}
$singleton=Object::getInstance();
$singleton->pass="admin";
$singleton->conn();
echo "<br/>";
$singleton->conn();
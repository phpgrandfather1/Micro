<?php
namespace Core;
class MyPDO{
    private $type;
    private $host;
    private $port;
    private $dbname;
    private $charset;
    private $user;
    private $pwd;
    private $pdo;
    private static $instance;
    private function __construct($param) {
        $this->initParam($param);
        $this->initPDO();
        $this->initException();
    }
    private function __clone(){
    }
    public static function getInstance($param=array()){
        if(!self::$instance instanceof  self)
            self::$instance=new self($param);
        return self::$instance;
    }
    //初始化参数
    private function initParam($param){
        $this->type=$param['type']??'mysql';
        $this->host=$param['host']??'127.0.0.1';
        $this->port=$param['port']??'3306';
        $this->dbname=$param['dbname']??'';
        $this->charset=$param['charset']??'utf8';
        $this->user=$param['user']??'';
        $this->pwd=$param['pwd']??'';
    }
    //实例化PDO对象
    private function initPDO(){
        try{
            $dsn="{$this->type}:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            $this->pdo=new \PDO($dsn,  $this->user,  $this->pwd);
        }catch(\PDOException $e){
            $this->showException($e);
        }
    }
    //设置PDO的异常模式
    private function initException(){
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);        
    }
    /*
     * 封装显示异常的方法
     * @param $e object 异常对象
     * @sql string 有异常的SQL语句
     */
    private function showException($e,$sql=''){
        if($sql!=''){
            echo 'SQL语句执行失败<br>';
            echo '错误的SQL语句是：'.$sql,'<br>';
        }
        echo '错误信息：'.$e->getMessage(),'<br>';
        echo '错误编号：'.$e->getCode(),'<br>';
        echo '错误文件：'.$e->getFile(),'<br>';
        echo '错误行号：'.$e->getLine(),'<br>';
        exit;
    }
    /*
     * 执行数据操作语句
     * @return int 返回受影响的记录数
     */
    public function exec($sql){
        try{
            return $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            $this->showException($e, $sql);            
        }
    }
    //获取最后插入的SQL语句自动增长的编号
    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
    //匹配数据的类型
    private function fetchType($type){
        switch($type){
            case 'assoc':
                return \PDO::FETCH_ASSOC;
            case 'num':
                return \PDO::FETCH_NUM;
            case 'both':
                return  \PDO::FETCH_BOTH;
            default:
                return \PDO::FETCH_ASSOC;
        }
    }
    //获取所有记录，返回二维数组
    public function fetchAll($sql,$type='assoc'){
        $stmt=  $this->pdo->query($sql);
        $type=  $this->fetchType($type);
        return $stmt->fetchAll($type);
    }
    //获取一条记录，返回一维数组
    public function fetchRow($sql,$type='assoc'){
        $stmt=  $this->pdo->query($sql);
        $type=  $this->fetchType($type);
        return $stmt->fetch($type);
    }
    //获取第一行第一列的数据
    public function fetchColumn($sql){
        $stmt=  $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }
}
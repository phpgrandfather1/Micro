<?php
class Framework{
    public static function run(){
        self::initConst();
        self::initConfig();
        self::initRoutes();
        self::initAutoLoad();
        self::initDispatch();
    }
    //定义路径常量
    private static function initConst(){
        define('DS', DIRECTORY_SEPARATOR);  //定义目录分隔符
        define('ROOT_PATH', getcwd().DS);      //getcwd()用来获取当前文件所在的目录
        define('APP_PATH', ROOT_PATH.'Application'.DS);
        define('FRAMEWORK_PATH', ROOT_PATH.'Framework'.DS);
        define('CONFIG_PATH', APP_PATH.'Config'.DS);
        define('CONTROLLER_PATH', APP_PATH.'Controller'.DS);
        define('MODEL_PATH', APP_PATH.'Model'.DS);
        define('VIEW_PATH', APP_PATH.'View'.DS);
        define('CORE_PATH', FRAMEWORK_PATH.'Core'.DS);
        define('LIB_PATH', FRAMEWORK_PATH.'Lib'.DS);
    }
    //引入配置文件
    private static function initConfig(){
        $GLOBALS['config']=require CONFIG_PATH.'config.php';
    } 
    //确定路由
    private static function initRoutes(){
        $p=$_REQUEST['p']??$GLOBALS['config']['app']['default_platform'];       //获取平台
        $c=$_REQUEST['c']??$GLOBALS['config']['app']['default_controller'];	//获取控制器名
        $a=$_REQUEST['a']??$GLOBALS['config']['app']['default_action'];		//获取方法名
        $c=ucfirst(strtolower($c)).'Controller';	//控制器以Controller结尾
        $a=strtolower($a).'Action';		//方法以Action结尾
        define('PLATFORM_NAME', $p);        //定义当前平台常量
        define('CONTROLLER_NAME', $c);  //定义当前控制器常量
        define('ACTION_NAME', $a);      //定义当前方法常量
        define('__URL__', CONTROLLER_PATH.$p.DS);  //当前控制器的目录
        define('__VIEW__',VIEW_PATH.$p.DS); //当前视图目录
    } 
    //注册自动加载类
    private static function initAutoLoad(){
        spl_autoload_register(function($class_name){
            $namespace= dirname($class_name);   //命名空间
            $class_name= basename($class_name); //类名
            if(in_array($namespace, array('Core','Lib')))   //加载框架类
                $path=FRAMEWORK_PATH.$namespace.DS.$class_name.'.class.php';
            elseif($namespace=='Model')     //加载模型
                $path=MODEL_PATH.$class_name.'.class.php';
            else                            //加载控制器
                $path=__URL__.$class_name.'.class.php';
            if(file_exists($path))
                require $path;
        });
    }
    //请求分发
    private static function initDispatch(){
        $controller_name='\Controller\\'.PLATFORM_NAME .'\\'.CONTROLLER_NAME; //拼接实例化的控制器
        $action_name=ACTION_NAME;
        $obj=new $controller_name();
        $obj->$action_name();
    }
}


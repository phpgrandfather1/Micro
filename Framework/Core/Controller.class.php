<?php
//基础控制器
namespace Core;
abstract class Controller{
    //封装成功后跳转
    protected function success($url,$info='',$time=1){
        $this->jump($url,$info,$time,'success');
    }
    //封装失败后跳转
    protected function error($url,$info='',$time=3){
        $this->jump($url,$info,$time,'error');
    }

    /*
     * 跳转的方法
     * @param $url string 跳转的URL地址
     * @param $infot string 显示的信息
     * @param $time int 停留的时间
     * @param $type string 成功或者失败， success|error
     */
    private function jump($url,$info='',$time=3,$type='error'){
        if($info=='')
            header("location:$url");
        else{
            echo <<<str
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="{$time};{$url}">
<title>无标题文档</title>
<style>
body{
    text-align:center;
    font-size:16px;
}
#success,#error{
    font-size:24px;
    color:#090;
    margin:20px auto;
}
#error{
    color:#F00;
}
</style>
</head>

<body>
<img src="/Public/images/{$type}.fw.png">
<div id="{$type}">{$info}</div>
<div>{$time}秒以后跳转</div>
</body>
</html>
str;
        }
    }
}
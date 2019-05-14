<?php
namespace app\index\controller;
use think\Controller;
use swooleobj\SwooleServer;
use app\common\lib\Task;
class Index extends Controller
{
    public function index()
    {
//	$res = SwooleServer::getInstance();
//	dump($res);
//	echo 'index';	
    }
    public function demo(){
	
//	$res = SwooleServer::getInstance();
//	$obj = $res->getRes();
//	dump($obj);
//	$obj->cookie('test','test');
	echo request()->action();
    }
    public function checkLogin(){
//	dump($_COOKIE['test']);
//	echo 1;
    }
    
}

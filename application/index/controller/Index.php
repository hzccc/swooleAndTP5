<?php
namespace app\index\controller;
use think\Controller;
use swooleobj\SwooleServer;
use app\common\lib\Task;
use think\Request;
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

    public function login(){
        $request = Request::instance();
        if($request->isGet()){
	    $res = SwooleServer::getInstance()->getRes();
	    $userInfo = [
		'userID' => 1,
		'userName'=>'黄英雄'
	    ];
	    $userInfo = serialize($userInfo);
	    $res->cookie('userInfo',$userInfo);
          //  setcookie('userInfo',$userInfo,time()+500,'/');
            //假装登录
            $arr = [
                "status" => 1,
                "info" => "登录成功",
            ];
            return json_encode($arr);
        }
    }

    public function checkLogin(){
        $request = Request::instance();
        if($request->isGet()){
            if(empty($_COOKIE['userInfo'])){
                $arr = [
                    "status" => 0,
                    "info" => "请重新登录",
                ];
                return json_encode($arr);
            } else {
                $arr = [
                    "status" => 1,
                    "info" => "登录中",
                ];
                return json_encode($arr);
            }
        }
    }
    
}

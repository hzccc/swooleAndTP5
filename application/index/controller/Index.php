<?php
namespace app\index\controller;
use think\Controller;
use swooleobj\SwooleServer;
use app\common\lib\Task;
use app\common\lib\Predis;
use think\Request;
use app\common\ApiResultUtils;
class Index extends Controller
{
    public function index() {
    }
    public function demo(){
	
	    echo request()->action();
    }

    public function login(){
        $request = SwooleServer::getInstance();
        if($request->isPost()){
            $res = $request->getRes();
            //获取值
            $userName = $request->param('username');
            $userPasswd = $request->param('password');
            $userId = rand(10,900);
            //校验登录...
            //todo
            $userInfo = [
                'userID' => $userId,
                'userName'=>$userName
            ];
            //序列化
            $userInfo = serialize($userInfo);
            $res->cookie('userInfo',$userInfo,time()+3600,'/');
            //登录成功返回数据
            return ApiResultUtils::ofSuccess('登录成功');
        }
    }

    public function checkLogin(){
        $request = Request::instance();
        if($request->isGet()){
            if(empty($_COOKIE['userInfo'])){
                return ApiResultUtils::ofFail('请重新登录');
            } else {
                $userInfo = unserialize($_COOKIE['userInfo']);
                //比对是否跟redis里面的id映射一致
                return ApiResultUtils::ofSuccess($userInfo);
            }
        }
    }

    public function delCookie(){

        $res = SwooleServer::getInstance()->getRes();
        $res->cookie('userInfo','',time()-60);
    }
    
}

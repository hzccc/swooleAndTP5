<?php
namespace app\index\controller;
use think\Controller;
use swooleobj\SwooleServer;
use app\common\lib\Task;
use app\common\lib\Predis;
use think\Request;
use app\common\ApiResultUtils;
use app\common\VerifyJwt;
use mysqlpool\MysqlPool;
use app\index\model\User;
use phpjwt\JWT;
class Index extends Controller
{
    public $key = '1gHuiop975cdashyex9Ud23ldsvm2Xq';
    public function index() {
        
    } 
    public function test(){
        $userDAO = new User();
        $res = $userDAO->querycount();
        dump($userDAO);
        // dump($res);
        // return ApiResultUtils::ofSuccess($res);
    }
    public function demo(){
        
        $userDAO = new User();
        $res = $userDAO->queryUserByNameAndPasswd('18778724200','395086');
        return ApiResultUtils::ofSuccess($res);
        $mysql = MysqlPool::getInstance()->getConnection(); 
        
    }
    public function login(){
        // 这里用swoole的方法获取到参数(只有POST是用swoole)Get用TP5
        $request = SwooleServer::getInstance();
        // $request = Request::instance();
        if($request->isPost()){
            $res = $request->getRes();
            //获取值
            $userName = $request->param('username');
            $userPasswd = $request->param('password');
            //过滤用户输入数据
            //todo
            //校验登录...
            $userDAO = new User();
            $result = $userDAO->queryUserByNameAndPasswd($userName,$userPasswd);
            if(empty($result)){
                return ApiResultUtils::ofFail('用户不存在');
            }
            //如果存在 则签发token
            $nowtime = time();
            $token = [
                'iss' => 'admin',
                'aud' => $userName,
                'iat' => $nowtime, //签发时间
                'nbf' => $nowtime + 1, //在什么时间之后该jwt才可用
                'exp' => $nowtime + 7200, 
                'data' => [
                    'userId' => $result['id'],
                    'userName' => $result['nick_name'],
                    'userAva' => $result['user_ava']
                ],

            ];
            $jwt = JWT::encode($token, $this->key);
            $userName = $result['nick_name'];
            $userAva = $result['user_ava'];
            $res->cookie('user_ava',$userAva,time()+3600,'/');
            $res->cookie('userName',$userName,time()+3600,'/');
            $res->cookie('token',$jwt,time()+7200,'/');
            //登录成功返回数据
            return ApiResultUtils::ofSuccess('登录成功');
        } else {
            return ApiResultUtils::ofFail('neet post');
        }
    }
    public function checkLogin(){
        $request = Request::instance();
        if($request->isGet()){
            if(!empty($_COOKIE['token'])){
                $res = VerifyJwt::JwtDecode($_COOKIE['token'],$this->key);
                if(empty($res)){
                    return ApiResultUtils::ofFail('请从新登陆');
                }
                $userInfo = (array)$res['data'];
                return ApiResultUtils::ofSuccess($userInfo);
            }
            return ApiResultUtils::ofFail('请重新登陆');
        }
    }
    
}

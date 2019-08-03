<?php
namespace app\index\controller;
use think\Controller;
use swooleobj\SwooleServer;
use app\common\lib\Task;
use app\common\lib\Predis;
use think\Request;
use app\common\ApiResultUtils;
use mysqlpool\MysqlPool;
use app\index\model\User;
use app\index\model\Friend;
use phpjwt\JWT;
class Chat extends Controller{

    /* 获取好友列表 */
    public function getFriendList(){
        $request = Request::instance();
        if($request->isGet()){
            $res = VerifyJwt::JwtDecode($_COOKIE['token']);
            if(!$res){
                return ApiResultUtils::ofFail('请从新登陆');
            }
            //这里应该还需要去查询一次用户存在不存在
            $userId = $request->get('userId');
            $friDAO = new Friend();
            $list = $friDAO->queryFriendList($userId);
            if(empty($list)){
                return ApiResultUtils::ofSuccess(array());
            }
            return ApiResultUtils::ofSuccess($list);
        }

    }

}
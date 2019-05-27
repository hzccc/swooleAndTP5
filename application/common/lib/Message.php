<?php 
namespace app\common\lib;

use swooleobj\SwooleServer;
use app\common\lib\Predis;
class Message{

    //简单的做一下Task任务分发
    //实际可以判断 
    public function msgHandOut($data,$fromId){
        //解析$data
        $swooleServer = SwooleServer::getInstance()->getSwooleServer();
        //$data = json_decode($data);
        $type = $data['type'];

        switch($type){
            case 'init':
                //这里做用户的映射关系
                break;
            case 'msg':
                $data = [
                    'data' =>[
                        'msg' => $data['data'],
                        'time' => $data['datetime'],
                        'userName' => $data['userName']
                    ], 
                    'fd' => $fromId
                ];
                $swooleServer->task($data);
                break;
        }
    }
}

<?php 
namespace app\common\lib;
use app\common\lib\Predis;
use swooleobj\SwooleServer;

class Task{



    public function sendAll($data){
        $swooleServer = SwooleServer::getInstance()->getSwooleServer();
	    $redis = Predis::getInstance();
        $clientList = $redis->sMembers();
        $formId = $data['fd'];
        dump($data['data']);
        foreach($clientList as $fd){
            if($fd == $formId){
                continue;
            }
            $data = json_encode($data['data']);
            $swooleServer->push($fd,$data);
        }
    }
}

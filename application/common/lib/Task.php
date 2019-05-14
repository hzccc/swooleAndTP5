<?php 
namespace app\common\lib;
use app\common\lib\Predis;
use swooleobj\SwooleServer;

class Task{



    public function sendAll($data){
        $swooleServer = SwooleServer::getInstance()->getSwooleServer();
	$redis = Predis::getInstance();
	$clientList = $redis->sMembers();
        foreach($clientList as $fd){
	    if($fd == $data['fd']){
		continue;
	    }
            $swooleServer->push($fd,$data['data']);
        }
    }
}

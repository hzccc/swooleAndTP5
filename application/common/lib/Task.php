<?php 
namespace app\common\lib;

use swooleobj\SwooleServer;

class Task{



    public function sendAll($data){
        $swooleServer = SwooleServer::getInstance()->getSwooleServer();
        foreach($swooleServer->connections as $fd){
            if($fd == $data['fd']){
                continue;
            }
            $swooleServer->push($fd,$data['data']);
        }
    }
}

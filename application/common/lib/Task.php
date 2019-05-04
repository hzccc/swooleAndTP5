<?php 
namespace app\common\lib;

use swooleobj\SwooleServer;

class Task{



    public function sendAll($data,$fromId){
        $swooleServer = SwooleServer::getInstance()->getSwooleServer();
        foreach($swooleServer->connections as $fd){
            if($fd == $fromId){
                continue;
            }
            $swooleServer->push($fd,$data);
        }
    }
}
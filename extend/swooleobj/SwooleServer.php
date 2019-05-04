<?php
namespace swooleobj;
use Swoole\WebSocket\Server;
class SwooleServer{

    private $instance;
    protected  $swoole_server;

    /**
     * 防止外部new
     */
    private function __construct(){

    }

    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
         }
         return self::$instance;
    }

    /**
     * 存储全局server对象
     * @param Server $server
     */
    public function setSwooleServer(Server $server){
        $this->swoole_server = $server;
    }

    public function getSwooleServer(){
        return $this->swoole_server;
    }
}
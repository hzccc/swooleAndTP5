<?php
namespace swooleobj;
use Swoole\WebSocket\Server;
use Swoole\Http\Response;
use Swoole\Http\Request;
class SwooleServer{

    private static $instance;
    protected $swoole_server;
    protected $res;
    protected $req;
    //后期优化成类对象树.
    protected $serverObj = [];
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

    public function setRes(Response $res){
        $this->res = $res;
    }
    public function getRes(){
        return $this->res;
    }
    /* 存储Request对象 */
    public function setReq(Request $req){
        $this->req = $req;
    }
    public function getReq(){
        return $this->req;
    }

    /* 获取GET请求方法 */
    public function isGet(){
        return $this->req->server['request_method'] == "GET";
       
    }
    /* 获取POST请求方法 */
    public function isPost(){
        return $this->req->server['request_method'] == "POST";
    }
    /* 获取post参数 */
    public function param($val){
        if(empty($val)){ 
            return $this->req->post;
        } else {
            return $this->req->post[$val];
        }
        
    }
}

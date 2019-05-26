<?php

include(__DIR__.'/../extend/swooleobj/SwooleServer.php');
use app\common\lib\Message;
use app\common\lib\Task;
use app\common\lib\Predis;
class WebSocket{
    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;
    CONST CHART_PORT = 8812;

    public $ws = null;

    public function __construct() {
        // 获取 key 有值 del
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        swooleobj\SwooleServer::getInstance()->setSwooleServer($this->ws);
        $this->ws->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/home/ftplogin/public/static",
                'worker_num' => 1,
                'task_worker_num' => 1 ,
            ]
        );

        $this->ws->on("start", [$this, 'onStart']);
        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("workerstart", [$this, 'onWorkerStart']);
        $this->ws->on("request", [$this, 'onRequest']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);
        $this->ws->on("close", [$this, 'onClose']);

        $this->ws->start();
    }

    /**
     * @param $server
     */
    public function onStart($server) {
        swoole_set_process_name("TPdemo");
    }
    /**
     * @param $server
     * @param $worker_id
     */
    public function onWorkerStart($server,  $worker_id) {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载框架里面的文件
        require __DIR__ . '/../thinkphp/start.php';
    }

    /**
     * request回调
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response) {
        $response->header('Content-Type','text/html; charset=utf-8');
        $uri = $request->server['request_uri'];
        if ($uri == '/favicon.ico') {
            $response->status(404);
            $response->end();
        }
        //暂时无法解决TP5 Request->isGet() 等方法获取请求类型所以暂时使用swoole req 跟 res
        swooleobj\SwooleServer::getInstance()->setReq($request);
        swooleobj\SwooleServer::getInstance()->setRes($response);
        $_SERVER  =  [];
        if(isset($request->server)) {
            foreach($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->header)) {
            foreach($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        dump($_SERVER);
        $_GET = [];
        if(isset($request->get)) {
            foreach($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
	    $_COOKIE = [];
		
        if(isset($request->cookie)) {
            foreach($request->cookie as $k => $v) {
                $_COOKIE[$k] = $v;
            }
        }
        $_FILES = [];
        if(isset($request->files)) {
            foreach($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        $_POST = [];
        if(isset($request->post)) {
            foreach($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        ob_start();
        // 执行应用并响应
        try {
            think\App::run()->send();
        }catch (\Exception $e) {
            // todo
        }
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($serv, $taskId, $workerId, $data) {

        // 分发 task 任务机制，让不同的任务 走不同的逻辑
        $obj = new app\common\lib\Task();

        $flag = $obj->sendAll($data);

        return $flag; 
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request) {
        // 记录fd与用户名关系
        echo $request->fd;
        dump($request);
        \app\common\lib\Predis::getInstance()->sAdd($request->fd);
        
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame) {
        //收到信息后逻辑提交给message类做 
        $data = [
            'data' => $frame->data,
            'fd' => $frame->fd
        ];
        $obj = new Message();
        $data = $frame->data;
        $data = json_decode($data,true);
        dump($data);
        $fromId = $frame->fd;
        $obj->msgHandOut($data,$fromId);
            
    }

    /**
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd) {
        // fd del
        \app\common\lib\Predis::getInstance()->sRem($fd);
        echo "clientid:{$fd}\n";
    }

}

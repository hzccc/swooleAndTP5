<?php
namespace mysqlpool;

use Swoole\Coroutine\Channel;

use Swoole\Coroutine\MySQL;
class MysqlPool {

    private $min;//最少连接数
    private $max;//最大连接数
    private $count;//当前连接数
    private $connections;//连接池组
    protected $spareTime;//用于空闲连接回收判断
    public static $instance; //单例模式
    private $inited = false;
    //数据库配置
    protected $dbConfig = array(
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => 'kb395086',
        'database' => 'chat_db',
        'charset' => 'utf8',
        'timeout' => 2,
    );

    private function __construct(){
        $this->min = 10;
        $this->max = 100;
        $this->spareTime = 10 * 3600;
        $this->connections = new Channel($this->max + 1);
    }
    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new MysqlPool();
        }
        return self::$instance;
    }
    /* 创建mysql对象 */
    protected function createDb(){
        $db = new MySQL();
        $db->connect(
            $this->dbConfig
        );
        return $db;
    }
    /* 初始化最小连接数 */
    public function init(){
        if ($this->inited) {
            return null;
        }
        for ($i = 0; $i < $this->min; $i++) {
            $obj = $this->createObject();
            $this->count++;
            $this->connections->push($obj);
        }
        // echo "当前连接数: ". $this->connections->length();
        return $this;
    }
    /* 创建 */
    protected function createObject(){
        $obj = null;
        $db = $this->createDb();
        if ($db) {
            $obj = [
                'last_use_time' => time(),
                'db' => $db,
            ];
        }
        return $obj;
    }
    /* 获取当前连接数 */
    public function getConnectionLength(){
        return $this->connections->length();
    }
    /* 获取连接 */
    public function getConnection($timeOut = 3){
        $obj = null;
        if ($this->connections->isEmpty()) {
            //若连接数没有达到最大则创建新连接
            if ($this->count < $this->max) {
                $this->count++;
                // echo "取出一个";
                $obj = $this->createObject();
            } else {
                //否则等待
                // echo "等待一个";
                $obj = $this->connections->pop($timeOut);
            }
        } else {
            // echo "直接取出";
            $obj = $this->connections->pop($timeOut);
        }
        return $obj;
    }
    /* 回收连接数..每次使用完对象都需要调用该方法 */
    public function free($obj){
        if ($obj) {
            $this->connections->push($obj);
        }
    }
    /* 处理空闲连接 */
    public function gcSpareObject()
    {
        //大约2分钟检测一次连接
        swoole_timer_tick(120000, function () {
            $list = [];
            // echo "开始检测回收空闲链接" . $this->connections->length() . PHP_EOL;
            if ($this->connections->length() < intval($this->max * 0.5)) {
                echo "请求连接数还比较多，暂不回收空闲连接\n";
            }#1
            while (true) {
                if (!$this->connections->isEmpty()) {
                    $obj = $this->connections->pop(0.001);
                    $last_used_time = $obj['last_used_time'];
                    if ($this->count > $this->min && (time() - $last_used_time > $this->spareTime)) {//回收
                        $this->count--;
                    } else {
                        array_push($list, $obj);
                    }
                } else {
                    break;
                }
            }
            foreach ($list as $item) {
                $this->connections->push($item);
            }
            unset($list);
        });
    }
}
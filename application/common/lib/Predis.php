<?php 
namespace app\common\lib;

use swooleobj\SwooleServer;

class Predis{
    public $redis;
    private static $instance;
    public $config = [
        'ip' => '127.0.0.1',
        'port' => 6379

    ];
    private function __construct(){
        $this->redis = new \Redis();
        $result = $this->redis->connect($this->config['ip'], $this->config['port']);
        if($result === false) {
            throw new \Exception('redis connect error');
        }
    }
    public static function getInstance(){
        if(empty(self::$instance)){
                self::$instance = new self();
        }
            return self::$instance;
    }
    /**
     * set
     * @param $key
     * @param $value
     * @param int $time
     * @return bool|string
     */
    public function set($key, $value, $time = 0 ){
        if(!$key) {
            return '';
        }
        if(is_array($value)) {
            $value = json_encode($value);
        }
        if(!$time) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->setex($key, $time, $value);
    }

    /**
     * get
     * @param $key
     * @return bool|string
     */
    public function get($key) {
        if(!$key) {
            return '';
        }

        return $this->redis->get($key);
    }


    /**
     * 增加在线人员
     */
    public function sAdd($fd){
	    return	$this->redis->sAdd('client',$fd);
    }
    /**
     * 删除在线人员
     */
    public function sRem($fd){
	    return $this->redis->sRem('client',$fd);

    }
	/**
     * 获取所有在线人员
     */
    public function sMembers($sMembers = 'client'){
	    return $this->redis->sMembers($sMembers);

    }

}

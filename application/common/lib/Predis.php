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

    public function sAdd($fd){
	return	$this->redis->sAdd('client',$fd);
    }

    public function sRem($fd){
	return $this->redis->sRem('client',$fd);

    }
	
    public function sMembers($sMembers = 'client'){
	return $this->redis->sMembers($sMembers);

    }

}

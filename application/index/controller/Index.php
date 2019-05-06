<?php
namespace app\index\controller;

class Index
{
    public function index()
    {	
	return 0; 
    }
    public function demo(){
	$obj = new \swoole_http_client('0.0.0.0',8811);
	$data = [
	    'test' => 'test'
	];
	$obj->setCookie($data);
    }
    
}

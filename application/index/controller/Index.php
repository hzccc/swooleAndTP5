<?php
namespace app\index\controller;

class Index
{
    public function index()
    {	
	return 0; 
    }
    public function demo(){
	setcookie('test','123');
    }
    
}

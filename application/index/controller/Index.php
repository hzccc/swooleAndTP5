<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
	echo request()->action();	
	return '这里是index/index/index'; 
    }
    public function demo(){
	
	echo '这里是index/index/demo';
	echo request()->action();
    }
    
}

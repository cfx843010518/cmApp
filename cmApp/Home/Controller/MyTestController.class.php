<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class MyTestController extends Controller {
    public function index(){
		$this->display('test');
	}
	
	public function sendToAndroid(){
		// echo 'da';
		require 'Public/src/JPush/JPush.php';
	}
	
	
	
	
	
}
<?php
// ������ϵͳ�Զ����ɣ�����������;
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
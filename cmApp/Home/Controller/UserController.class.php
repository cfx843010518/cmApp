<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index(){
	// $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
	// echo '这是Home模块';
	$this->display('test');
	}
	
	public function getUrl(){
		$PHP_SELF=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
		$url='http://'.$_SERVER['HTTP_HOST'].substr($PHP_SELF,0,strripos($PHP_SELF, 'cmApp')+strlen('cmApp/'));		//获取项目的url
		return $url;
	}
	//验证用户的登录
	public function checkUser(){
		$ret = array('ret'=>array(),'status'=>'lose');
		$user_account = I('post.user_account','');
		if(!$user_account==''){
			$user_password = I('post.user_password');
			$user_role = I('post.user_role');
			if($user_role==1){
				$userModel = M('student');
				// var_dump($userModel);
				$condition['stu_no'] = $user_account;
				$condition['stu_password'] = $user_password;
				
			}else if($user_role==2){
				$userModel = M('teacher');
				$condition['tea_no'] = $user_account;
				$condition['tea_password'] = $user_password;
			}else{
				$userModel = M('homeAdmin');
				$condition['home_admin_account'] = $user_account;
				$condition['home_admin_password'] = $user_password;

			}
			$rs = $userModel->where($condition)->find();
			if($rs!=null){
				$PHP_SELF=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
				$url='http://'.$_SERVER['HTTP_HOST'].substr($PHP_SELF,0,strripos($PHP_SELF, 'cmApp')+strlen('cmApp/'));		//获取项目的url
				if($user_role==3){
					$ret = array('ret'=>$rs,'status'=>'success');
					echo json_encode($ret);
					return;
				}
				else if($user_role==2){
					$photo = $rs['tea_photo'];
					$rs['tea_photo'] = $url.'Public/teacher/images/'.$photo;
				}
				else{
					$photo = $rs['stu_photo'];
					$rs['stu_photo'] = $url.'Public/student/images/'.$photo;
					$condition['stu_no'] = $rs['stu_no'];
					$temp = M('authority')->where($condition)->find();		//查询该用户权限
					if($temp!=''){
						$rs['is_authority'] = $temp['authority'];
					}else{
						$rs['is_authority'] = 0;
					}
				}
				//var_dump($rs);
				$ret = array('ret'=>$rs,'status'=>'success');
				
			}
		}
		echo json_encode($ret);
	}
	
	//修改默认密码
	public function updatePassword(){
		$ret = array('status'=>'lose');
		$user_id = I('post.user_id','');
		if($user_id != ''){
			$user_password = I('post.user_password','');
			$user_role = I('post.user_role','');
			if($user_role == 1){
				$userModel = M('student');
				$rs = $userModel->find($user_id);
				if($rs!=null){
					$rs['stu_password'] = $user_password;
					$ret = array('status'=>'success');
				}
			}
			else{
				$userModel = M('teacher');
				$rs = $userModel->find($user_id);
				if($rs!=null){
					$rs['tea_password'] = $user_password;
					$ret = array('status'=>'success');
				}
			}
			$userModel->save($rs);
		}
		echo json_encode($ret);
	}
	
	//修改默认头像
	public function updatePhoto(){
		$ret = array('status'=>'lose');
		$user_id = I('post.user_id','');
		if($user_id != ''){
			$user_role = I('post.user_role');
			//处理文件上传 
			if(isset($_FILES['user_photo']['name'])){
				// echo '来到这里没有';
				$_FILES['user_photo']['name'] = '0'.$user_id.'.jpg';				//修改图片名字，便于管理
				if($user_role==1){
					$userModel = M('student');
					$target_path = 'Public/student/images/';
				}else if($user_role==2){
					$userModel = M('teacher');
					$target_path = 'Public/teacher/images/';
				}
				$target_path = $target_path.basename($_FILES['user_photo']['name']);
				$img_type = $_FILES['user_photo']['type'];		//获取图片类型
				$img_size = $_FILES['user_photo']['size'];
				// echo $img_type;
				// recoreImg($target_path,$img_type,$img_size);		//记录上传的图片信息
				if(($img_type=='image/jpeg'&&$img_size<10485760)||($img_type=='image/png'&&$img_size<10485760)||($img_type=='application/octet-stream'&&$img_size<10485760)){
					if(move_uploaded_file($_FILES['user_photo']['tmp_name'],$target_path)){
						$user_photo = basename($_FILES['user_photo']['name']);
						$userModel->find($user_id);
						if($user_role==1){
							$userModel->stu_photo = $user_photo;
						}else if($user_role==2){
							$userModel->tea_photo = $user_photo;
						}
						$userModel->save();
						$url = $this->getUrl(); 
						$ret = array('status'=>'success','new_photo'=>$url.$target_path);
					}
				}
			}
		}
		echo json_encode($ret);
	}
	
	//获取学生活动
	public function getStudentActive(){
		$ret = array('res'=>null,'status'=>'lose');
		$user_id = I('get.user_id','');
		if($user_id != ''){
			$condition['active_type'] = 1;
			$condition['is_approw'] = 1;
			$condition['is_share'] = 1;
			$rs = M('roomApply')->where($condition)->order('apply_date desc')->select();
			$res = array('mes'=>$rs,'mes_num'=>count($rs));
			$ret = array('res'=>$res,'status'=>'success');
		}
		echo json_encode($ret);
	}
	
	public function getTeacherActive(){
		$ret = array('res'=>null,'status'=>'lose');
		$user_id = I('get.user_id','');
		if($user_id != ''){
			$condition['active_type'] = 2;
			$condition['is_approw'] = 1;
			$condition['is_share'] = 1;
			$rs = M('roomApply')->where($condition)->order('apply_date desc')->select();
			$res = array('mes'=>$rs,'mes_num'=>count($rs));
			$ret = array('res'=>$res,'status'=>'success');
		}
		echo json_encode($ret);
	}
	
	//获取活动
	public function getActive(){
		$ret = array('res'=>null,'status'=>'lose');
		$user_id = I('get.user_id','');
		if($user_id != ''){
			$condition['is_approve'] = 1;
			$condition['is_share'] = 1;
			$condition['active_type'] = 1;
			$rs = M('roomApply')->where($condition)->order('apply_date desc')->limit(5)->select();
			$res = array('mes'=>$rs,'mes_num'=>count($rs));
			$ret = array('res'=>$res,'status'=>'success');
		}
		echo json_encode($ret);
	}
	
	
	public function myTest(){
		var_dump($_POST);
	}
	
}
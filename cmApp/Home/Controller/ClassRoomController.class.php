<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ClassRoomController extends Controller {
    public function index(){
	$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
	// echo '这是Home模块';
	$this->display('test');
	}
	
	//查询空教室
	public function searchRoomFree(){
		$ret = array('res'=>array(),'status'=>'lose','mes_num'=>0);
		$room_type_id = I('get.room_type_id','');		//课室类型(必选)
		$week_day = I('get.week_day','');				//1是星期一  2是星期二 以此类推
		//$room_name = I('get.room_name');
		//var_dump($week_day);
		$time_interval1 = I('get.time_interval','');		//时间段 (必选)
		$week_record = I('get.week_record','');			//周次(必选)
			
		$schoolTimeModel = D('schoolTime');
		$rs = $schoolTimeModel->getSearchRoom($room_type_id,$week_day,$time_interval1,$week_record);
		// var_dump($rs);
		// echo '来到这里没有';
		if($rs!=null){
			// 开始处理数据
			// 1.初始化数据
			$room_name = $rs[0]['room_name'];
			// echo $room_name;
			$week_record = $rs[0]['week_record'];
			$week_day = $rs[0]['week_day'];
			$can_use = $rs[0]['can_use'];
			$room_volume = $rs[0]['room_volume'];
			$time_interval = '';
			foreach($rs as $key=>$val){
				if($val['room_name'] == $room_name){
					if($val['week_day'] == $week_day){
						$time_interval .= $val['time_interval'].',';
						
					}else{
						$res[] = array('room_name'=>$room_name,'can_use'=>$can_use,'room_volume'=>$room_volume,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
						// 初始化参数
						$room_name = $val['room_name'];
						$week_record = $val['week_record'];
						$week_day = $val['week_day'];
						$time_interval = $val['time_interval'].',';
						$can_use = $val['can_use'];
						$room_volume = $val['room_volume'];
						// echo '来过这里吗';
					}
				}
				else{
					// echo '来到这里没有'.'<br/>';
					$res[] = array('room_name'=>$room_name,'can_use'=>$can_use,'room_volume'=>$room_volume,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
					// 初始化参数
					$room_name = $val['room_name'];
					$week_record = $val['week_record'];
					$week_day = $val['week_day'];
					$time_interval = $val['time_interval'].',';
					$can_use = $val['can_use'];
					$room_volume = $val['room_volume'];
				}
			}
			// 处理最后一次数据
			$res[] = array('room_name'=>$room_name,'can_use'=>$can_use,'room_volume'=>$room_volume,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
			
			//单独有空的去掉
			//准备工作
			$time_interval1 = explode('-',$time_interval1);
			// var_dump($time_interval);
			for($i=$time_interval1[0];$i<=$time_interval1[1];$i++){
				$myArray[] = $i;
			}
			//去掉
			foreach($res as $key=>$val){
				$time_intervals = $val['time_interval'];
				$time_intervals = explode(',',$time_intervals);
				if(count($myArray)==count($time_intervals)-1){
					$res2[] =  $val;
				}
			}
			$ret = array('ret'=>$res2,'status'=>'success','mes_num'=>count($res2));
		}
		echo json_encode($ret);
		// var_dump($rs);
	}
	
	public function applyTest(){
		$this->display('applyTest');
	}
	
	//申请教室
	public function applyRoom(){
		// $run = include 'config.php';
		$ret =array('result'=>'lose');
		$user_id = I('post.user_id','');
		if($user_id != ''){
			$active_type = I('post.active_type');		//获得活动类型  1代表学生活动  2代表教师活动 
			$active_theme = I('post.active_theme');		//活动主题
			$time_interval = I('post.time_interval');	//活动时间段
			$week_record = I('post.week_record');		//活动周
			$week_day = I('post.week_day');				//活动星期
			$sponsor = I('post.sponsor');				//主办方
			$room_name = I('post.room_name');			//申请的教室
			$apply_reason = I('post.apply_reason');		//申请理由
			$is_share = I('post.is_share');
			$roomApplyModel = M('roomApply');
			$data['user_id'] = $user_id;
			$data['active_type'] = $active_type;
			$data['active_theme'] = $active_theme;
			$data['time_interval'] = $time_interval;
			$data['week_record'] = $week_record;
			$data['week_day'] = $week_day;
			$data['sponsor'] = $sponsor;
			$data['apply_reason'] = $apply_reason;
			$data['room_name'] = $room_name;
			$data['is_share'] = $is_share;
			$time = date('Y-m-d');
			$data['apply_date'] = $time;
			$room_apply_id = $roomApplyModel->add($data);
			$condition['week_record'] = $week_record;
			$condition['week_day'] = $week_day;
			$condition['room_name'] = $room_name;
			$time_interval = explode('-',$time_interval);
			$condition['time_interval'] = array('in',$time_interval);
			$data2['room_status'] = 1;
			M('schoolTime')->where($condition)->save($data2);
			if(isset($room_apply_id)){
				$ret = array('result'=>'success');
			}
			echo json_encode($ret);
		}
	}
	
	// public function setTime($room_apply_id){
		// 设置定时器，10天后，显示记录过期
		// ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
			// set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
		// $interval=60;// 每隔半小时运行
		// sleep($interval);
		// $roomApplyModel = M('roomApply');
		// $roomApplyModel->find($room_apply_id);
		// $roomApplyModel->is_approve = 2;
		// $roomApplyModel->save();
	// }
	
			// $roomUseModel = D('roomUse');
			// $rs = $roomUseModel->getSearchRoom($room_type_id,$week_day,$time_interval_id,$week_record);
			// $roomModel = M('room');
			// if($rs!=null){									//不等于空说明查到了数据，要进行反向查询
				// foreach($rs as $key=>$val){					//获得已查询到的课室id，进行反向查询
					// $array[$key] = $val['room_use_id'];
				// }
				// $condition['room_use_id'] = array('in',$array);
			// }
			// $rs1 = $roomUseModel->where($condition)->select();
			
			// if($room_type_id != ''){
				// $condition['room_type_id'] = $room_type_id;
			// }
			// $rs = $roomModel->getRoomFree($room_type_id,$week_day,$time_interval_id,$week_record);
			// $rs = $roomModel->where($condition)->join('LEFT JOIN cm_room_use ON cm_room.room_id = cm_room_use.room_id')->select();
			// $sql = "select * from kg_teacher,kg_classroom,kg_record_subject,kg_classes,kg_subject,kg_time_interval where kg_classroom.classroom_id = kg_record_subject.classroom_id and kg_classes.classes_id = kg_record_subject.classes_id and kg_subject.subject_id = kg_record_subject.subject_id and kg_teacher.tea_id = kg_record_subject.tea_id and kg_time_interval.time_interval_id = kg_record_subject.time_interval_id and kg_classroom.classroom_id =$classRoom_id and week_day='$week_day' and term_id = $term_id";
			// echo $sql;
			// $rs = execQuery($sql);
			
			// $condition['week_day'] = $week_day;
			// $rs = $roomUseModel->where($condition)->join('cm_time_interval ON cm_room_use.time_interval_id = cm_time_interval.time_interval_id')->join('cm_room ON cm_room_use.room_id = cm_room.room_id')->select();
			// var_dump($rs);
			// $res = array();
			// foreach($rs as $key=>$val){
				// $array1 = explode('-',$val['week_record']);
			
				
				// $array2 = explode('-',$val['time_interval']);
				
				
				// if((!(($week_record1<$array1[0]&&$week_record2<$array1[0])||($week_record1>$array1[1]&&$week_record2>$array1[1]))) && !(($time_interval_array[0]<$array2[0]&&$time_interval_array[1]<$array2[0])||($time_interval_array[0]>$array2[1]&&$time_interval_array[1]>$array2[1]))){
					// $res[$key] = $val;
				// }
			// }
			// $this->assign('res',$res);
			// $this->display('result');
		// }	
	
	public function test(){
		$termModel = M('term');
		$terms = $termModel->select();
		
		$roomModel = M('room');
		$rooms = $roomModel->select();
		
		$timeIntervalModel = M('timeInterval');
		$time_interval = $timeIntervalModel->select();
		
		$this->assign('terms',$terms);	
		$this->assign('rooms',$rooms);	
		$this->assign('time_interval',$time_interval);	
		
		$this->display('showMessage');
	}
	
}
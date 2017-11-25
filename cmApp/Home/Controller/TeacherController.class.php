<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class TeacherController extends Controller {
    public function index(){
	$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
	echo '这是Home模块';
	$this->display('test');
	}
	
	//获得我的课表
	public function getMyschedules(){
		$ret = array('ret'=>null,'status'=>'lose');
		$tea_id = I('get.tea_id','');
		if($tea_id != ''){
			$teacherModel = D('teacher');
			$rs = $teacherModel->getMySch($tea_id);
			// var_dump($rs);
			$array = array('1'=>'one',2=>'two',3=>'three',4=>'four',5=>'five',6=>'six',7=>'seven');
			foreach($rs as $key=>$val){		//重新封装下数据
				$res['tea_sch_id'] = $val['tea_sch_id'];
				$res['subject_name'] = $val['subject_name'];
				$res['time'] = $val['time'];
				$res['week_day'] = $val['week_day'];
				$res['room_name'] = $val['room_name'];
				$res['classes_name'] = $val['classes_name'];
				$term_id = $val['term_id'];
				$t = M('term')->find($term_id);
				$res['term_name'] = $t['term_name'];
				$all[$array[$val['week_day']]][] = $res; 
			}
			$ret = array('tea_id'=>$rs[0]['tea_id'],'tea_no'=>$rs[0]['tea_no'],'tea_name'=>$rs[0]['tea_name'],'mes'=>$all,'mes_num'=>count($res));
			$ret = array('ret'=>$ret,'status'=>'success');
		}
		echo json_encode($ret);
	}
	
	//显示教师调课的课程
	public function showChangeClass(){
		$ret = array('ret'=>null,'status'=>'lose');
		$tea_id = I('get.tea_id','');
		if($tea_id != ''){
			$teacherModel = D('teacher');
			$rs = $teacherModel->getMySch($tea_id);
			// var_dump($rs);
			//首先处理下数据，帮相同课程的时间段合并
			//初始化数据
			$array = array('1'=>'one',2=>'two',3=>'three',4=>'four',5=>'five',6=>'six',7=>'seven');
			foreach($rs as $key=>$val){		//重新封装下数据
				$res['tea_sch_id'] = $val['tea_sch_id'];
				$res['subject_name'] = $val['subject_name'];
				// $time = $val['time'];
				// $time = explode('/',$time);
				// $one = explode('(',$time[0]);
				// $two = explode('(',$time[1]);
				// $ones = explode(')',$one[1]);
				// $twos = explode(')',$two[1]);
				// $oneWeek = $one[0];
				// $twoWeek = $two[0];
				// $oneWeekArray = explode('-',$oneWeek);
				// $twoWeekArray = explode('-',$twoWeek);
				// for($i=$oneWeekArray[0];$i<=$oneWeekArray[1];$i++){
					// $temp1[] = $i;
				// }
				// for($i=$twoWeekArray[0];$i<=$twoWeekArray[1];$i++){
					// $temp2[] = $i;
				// }
				// var_dump($temp1);
				// var_dump($temp2);
				// $arr3=array_intersect($temp1,$temp2);
				
				// unset($temp1);
				// unset($temp2);
				$res['time'] = $val['time'];
				$res['week_day'] = $val['week_day'];
				$classes_name = explode('【',$val['classes_name']);
				$res['classes_name'] = $classes_name[0];
				$res['room_name'] = $val['room_name'];
				$term_id = $val['term_id'];
				$t = M('term')->find($term_id);
				$res['term_name'] = $t['term_name'];
				$all[$array[$val['week_day']]][] = $res; 
			}
			$ret = array('tea_id'=>$rs[0]['tea_id'],'tea_no'=>$rs[0]['tea_no'],'tea_name'=>$rs[0]['tea_name'],'mes'=>$all,'mes_num'=>count($res));
			$ret = array('ret'=>$ret,'status'=>'success');
		}
		echo json_encode($ret);
	}
	
	//显示教师调课的符合教室
	public function showConditionRoom(){
		$ret = array('res'=>array(),'status'=>'lose','mes_num'=>0,'lose_mes'=>'');
		$mes = $_POST['mes'];	//获取json数据
		$mes = json_decode($mes);	//解析json数据
		$mes = $mes->ret;
		// var_dump($mes);
		$tea_sch_id = $mes->tea_sch_id;
		if($tea_sch_id != ''){
			//先判断是否是调走的课
			$conMy['week_recordB'] = $mes->week_recordB;
			$conMy['tea_sch_id'] = $tea_sch_id;
			// var_dump($conMy);
			$result = M('teaChangeschedule')->where($conMy)->find();
			// var_dump($result);
			if($result != null){
				$ret = array('res'=>array(),'status'=>'lose','mes_num'=>0,'lose_mes'=>'此课程此时刻已被调走');
			}
			else{
				$room_type_id = 1;
				//查询该教师的学生在调课的时间段是否有空
				$teacherSchedulesModel = M('teacherSchedules');
				$teacherSchedulesModel->find($tea_sch_id);
				$classes_name = $teacherSchedulesModel->classes_name;
				$classes_name = explode('【',$classes_name);
				// var_dump($classes_name[0]);
				$condition2['classes_name'] = $classes_name[0];
				$condition2['week_record'] = $mes->week_recordA;	
				$condition2['week_day'] = $mes->week_day;
				$time_interval = explode('-',$mes->time_interval);		//调2节或4节的
				for($i=$time_interval[0];$i<=$time_interval[1];$i++){
					$temp[] = $i;
				}
				$condition2['time_interval'] = array('in',$temp);
				// var_dump($condition2);
				$rs = M('schoolTime')->where($condition2)->find();		//判断学生在调课时间段是否有课
				// var_dump($rs);
				// var_dump($condition2);
				if($rs==null){
					//为空才允许调课
					$week_recordA = $mes->week_recordA;		//获取要调课后的周数
					$week_day = $mes->week_day;				//调课后的星期
					$time_interval = $mes->time_interval;		//调课后的时间段
					$schoolTimeModel = D('schoolTime');
					$rs = $schoolTimeModel->getSearchRoom($room_type_id,$week_day,$time_interval,$week_recordA);
					//var_dump($rs);
					if($rs!=null){
						// 开始处理数据
						// 1.初始化数据
						$room_name = $rs[0]['room_name'];
						// echo $room_name;
						$week_record = $rs[0]['week_record'];
						$week_day = $rs[0]['week_day'];
						$time_interval = '';
						$term_id = $rs[0]['term_id'];
						$room_volume = $rs[0]['room_volume'];
						$can_use = $rs[0]['can_use'];
						foreach($rs as $key=>$val){
							if($val['room_name'] == $room_name){
								if($val['week_day'] == $week_day){
									$time_interval .= $val['time_interval'].',';
								}
								else{
									$res[] = array('term_id'=>$term_id,'room_name'=>$room_name,'can_use'=>$can_use,'room_volume'=>$room_volume,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
									// 初始化参数
									$room_name = $val['room_name'];
									$week_record = $val['week_record'];
									$week_day = $val['week_day'];
									$time_interval = $val['time_interval'].',';
									$term_id = $val['term_id'];
									$room_volume = $val['room_volume'];
									$can_use = $val['can_use'];
								}
							}
							else{
								// echo '来到这里没有'.'<br/>';
								$res[] = array('term_id'=>$term_id,'room_name'=>$room_name,'can_use'=>$can_use,'room_volume'=>$room_volume,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
								// 初始化参数
								$room_name = $val['room_name'];
								$week_record = $val['week_record'];
								$week_day = $val['week_day'];
								$time_interval = $val['time_interval'].',';
								$room_volume = $val['room_volume'];
								$can_use = $val['can_use'];
							}
						}
						// 处理最后一次数据
						$res[] = array('term_id'=>$term_id,'room_name'=>$room_name,'can_use'=>$can_use,'room_volume'=>$room_volume,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
						//单独有空的去掉
						foreach($res as $key=>$val){
							$time_intervals = $val['time_interval'];
							$time_intervals = explode(',',$time_intervals);
							if(count($temp)==count($time_intervals)-1){
								$res2[] = $val;
							}
						}
						$ret = array('ret'=>$res2,'status'=>'success','mes_num'=>count($res2));
					}	
				}
				else{
					$ret = array('res'=>array(),'status'=>'lose','mes_num'=>0,'lose_mes'=>'此时间段该学生有课');
				}
			}
		}
		echo json_encode($ret);
	}
	
	
	//显示教师调课的符合教室
	public function showConditionRoom2(){
		$ret = array('res'=>array(),'status'=>'lose','mes_num'=>0);
		$mes = $_POST['mes'];	//获取json数据
		$mes = json_decode($mes);	//解析json数据
		$mes = $mes->ret;
		// var_dump($mes);
		$tea_sch_id = $mes->tea_sch_id;
		if($tea_sch_id != ''){
			$room_type_id = 1;
			//查询该教师的学生在调课的时间段是否有空
			$teacherSchedulesModel = M('teacherSchedules');
			$teacherSchedulesModel->find($tea_sch_id);
			$classes_name = $teacherSchedulesModel->classes_name;
			$classes_name = explode('【',$classes_name);
			$condition2['classes_name'] = $classes_name[0];
			$rs = M('classes')->where($condition2)->find(); 
			$classes_id = $rs['classes_id'];
			$condition['classes_id'] = $classes_id;			//赋予条件查询
			$condition['week_day'] = $mes->week_day;
			$rs = M('studentSchedules')->where($condition)->select();
			var_dump($rs);
			$week_recordA = $mes->week_recordA;
			$init = 0;
			foreach($rs as $key=>$val){
				$time = $val['time'];
				// $time = explode(',',$time);
				$time = explode('(',$time);
				var_dump($time);
				$week = explode('-',$time[0]);
				if($week_recordA<$week[0]||$week_recordA>$week[1]){
					$init++;
				}
				//要是周数在中间就要看时间段
				else{
					$time_interval = explode(')',$time[1]);
					$time_interval = explode(',',$time_interval[0]);
					$time_interval_new = explode('-',$mes->time_interval);
					for($i=$time_interval_new[0];$i<=$time_interval_new[1];$i++){
						$times[] = $i;
					}
					$intersection = array_intersect($time_interval,$time_interval_new);
					if(count($intersection)==0){
						$init++;
					}

				}
				unset($times);
			}
			if($init==count($rs)){
				//说明全通过，可以调课
				echo '说明全通过，可以调课';
			}
			// $condition2['week_record'] = $mes->week_recordA;	
			// $condition2['week_day'] = $mes->week_day;
			// $time_interval = explode('-',$mes->time_interval);		//调2节或4节的
			// for($i=$time_interval[0];$i<=$time_interval[1];$i++){
				// $temp[] = $i;
			// }
			// $condition2['time_interval'] = array('in',$temp);
			// $rs = M('schoolTime')->where($condition2)->find();		//判断学生在调课时间段是否有课
			// if($rs==null){
				// 为空才允许调课
				// $week_recordA = $mes->week_recordA;		//获取要调课后的周数
				// $week_day = $mes->week_day;				//调课后的星期
				// $time_interval = $mes->time_interval;		//调课后的时间段
				// $schoolTimeModel = D('schoolTime');
				// $rs = $schoolTimeModel->getSearchRoom2($room_type_id,$week_day,$time_interval,$week_recordA);
				// var_dump($rs);
				// if($rs!=null){
					// 开始处理数据
					// 1.初始化数据
					// $room_name = $rs[0]['room_name'];
					// echo $room_name;
					// $week_record = $rs[0]['week_record'];
					// $week_day = $rs[0]['week_day'];
					// $time_interval = '';
					// $term_id = $rs[0]['term_id'];
					// foreach($rs as $key=>$val){
						// if($val['room_name'] == $room_name){
							// if($val['week_day'] == $week_day){
								// $time_interval .= $val['time_interval'].',';
							// }
							// else{
								// $res[] = array('term_id'=>$term_id,'room_name'=>$room_name,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
								// 初始化参数
								// $room_name = $val['room_name'];
								// $week_record = $val['week_record'];
								// $week_day = $val['week_day'];
								// $time_interval = $val['time_interval'].',';
								// $term_id = $val['term_id'];
							// }
						// }
						// else{
							// echo '来到这里没有'.'<br/>';
							// $res[] = array('term_id'=>$term_id,'room_name'=>$room_name,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
							// 初始化参数
							// $room_name = $val['room_name'];
							// $week_record = $val['week_record'];
							// $week_day = $val['week_day'];
							// $time_interval = $val['time_interval'].',';
						// }
					// }
					// 处理最后一次数据
					// $res[] = array('term_id'=>$term_id,'room_name'=>$room_name,'week_record'=>$week_record,'week_day'=>$week_day,'time_interval'=>$time_interval);
					// $ret = array('ret'=>$res,'status'=>'success','mes_num'=>count($res));
				// }	
			// }
		}
		// echo json_encode($ret);
	}
	
	
	//修改教师调课
	public function changeClassSchedule(){
		$ret = array('res'=>array(),'status'=>'lose','mes_num'=>0);
		$tea_sch_id = I('get.tea_sch_id','');
		if($tea_sch_id != ''){
			// $term_id = I('term_id',''),
			$week_recordB = I('get.week_recordB','');	//调课前的周
            $room_name = I('get.room_name','');			//调课后的教室
			$week_recordA = I('get.week_recordA','');	//调课后的周
			$time_interval = I('time_interval','');		//调课后的时间段
			$week_day = I('week_day','');				//调课后的天
			$data['tea_sch_id'] = $tea_sch_id;
			$data['week_recordB'] = $week_recordB;
			$data['week_recordA'] = $week_recordA;
			$data['week_dayA'] = $week_day;
			$data['time_interval'] = $time_interval;
			$data['apply_date'] = date('Y-m-d');
			$data['room_name_new'] = $room_name;
			$data['is_allow'] = 0;
			$ret = M('teaChangeschedule')->add($data);
			if($ret!=0){
				//封装好数据
				$teacherSchedulesModel = M('teacherSchedules');
				$rs = $teacherSchedulesModel->field('cm_teacher.tea_no,tea_name,term_name,subject_name,time,week_day,room_name,classes_name,cm_term.term_id')->join('cm_teacher ON cm_teacher_schedules.tea_no = cm_teacher.tea_no')->join('cm_term ON cm_teacher_schedules.term_id = cm_term.term_id')->find($tea_sch_id);
				// var_dump($rs);
				$ret = array('status'=>'success');
				//同时将调课后课室设置为不空
				$schoolTime = M('schoolTime');
				$condition['week_record'] = $week_recordA;
				$condition['term_id'] = $rs['term_id'];
				$condition['week_day'] = $week_day;
				$condition['room_name'] = $room_name;
				$time_interval = explode(',',$time_interval);
				// var_dump($time_interval);
				for($i=0;$i<count($time_interval)-1;$i++){
					
					$temp[] = $time_interval[$i];
				}
				$condition['time_interval'] = array('in',$temp);
				// var_dump($condition);
				// var_dump($temp);
				$data2['room_status'] = 1;
				$data2['subject_name'] = $rs['subject_name'];
				$data2['teacher_name'] = $rs['tea_name'];
				$classes_name = explode('【',$rs['classes_name']);
				// var_dump($condition);
				$data2['classes_name'] = $classes_name[0];
				// var_dump($data2);
				$schoolTime->where($condition)->save($data2);
				// var_dump($a);
			}
		}
		echo json_encode($ret);
	}
	
	//查看自己的调课记录
	public function watchMyApply(){
		$ret = array('res'=>null,'status'=>'lose');
		$tea_no = I('get.tea_no','');
		if($tea_no != ''){
			$condition['tea_no'] = $tea_no;
			$rs = M('teacherSchedules')->where($condition)->join('cm_tea_changeschedule ON cm_teacher_schedules.tea_sch_id = cm_tea_changeschedule.tea_sch_id')->join('cm_term ON cm_teacher_schedules.term_id = cm_term.term_id')->order('apply_date desc')->select();
			// var_dump($rs);
			if($rs!=null){
				$ret = array('res'=>$rs,'status'=>'success','mes_num'=>count($rs));
			}
		}
		echo json_encode($ret);
	}
	
}
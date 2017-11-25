<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class StudentController extends Controller {
    public function index(){
		
		$this->display('test');
	}
	
	//获得我的课表
	public function getMyRecordSubject(){
		$ret = array('ret'=>array(),'status'=>'lose');
		$stu_id = I('get.stu_id','');
		if($stu_id != ''){
			$studentModel = D('student');
			$rs = $studentModel->getStuSubjectTable($stu_id);
			foreach($rs as $key=>$val){							//重新封装一下数据
				$ret1[$key]['room_name'] = $val['room_name'];
				$ret1[$key]['time'] = $val['time'];
				$ret1[$key]['week_day'] = $val['week_day'];
				$ret1[$key]['subject_name'] = $val['subject_name'];
				$ret1[$key]['teacher_name'] = $val['tea_name'];
			}
			$weekArray = array(1=>'one',2=>'two',3=>'three',4=>'four',5=>'five',6=>'six',7=>'seven');
			foreach($ret1 as $key=>$val){					//相相同的天数的数组合并
				$array[$weekArray[$val['week_day']]][] = $val;
			}		
			// var_dump($rs);
			$ret3 = array('stu_id'=>$rs[0]['stu_id'],'stu_no'=>$rs[0]['stu_no'],'stu_name'=>$rs[0]['stu_name'],'stu_major'=>$rs[0]['stu_major'],'classes_name'=>$rs[0]['classes_name'],'term_name'=>$rs[0]['term_name'],'stu_subject'=>$array,'subject_num'=>count($ret1));
			$ret = array('ret'=>$ret3,'status'=>'success');
		}
		// var_dump($rs);
		echo json_encode($ret);
	}
	
	
	//查看社团活动
	public function WatchOrActive(){
		$ret = array('ret'=>array(),'mes_num'=>0,'status'=>'lose');
		// $stu_id = I('get.stu_id','');
		//if(!$stu_id==""){
		$roomApplyModel = M('roomApply');
		$condition['is_approve'] = 1 ;
		$condition['is_share'] = 1;
		$rs = $roomApplyModel->where($condition)->select();
		if($rs!=null){
			$ret = array('ret'=>$rs,'mes_num'=>count($rs),'status'=>'success');
			
		}
		echo json_encode($ret);
	}
	
	//获得我的课表2
	public function getMyRecordSubject2(){
		$ret = array('ret'=>array(),'status'=>'lose');
		$stu_id = I('get.stu_id','');
		if(!$stu_id==""){
			$studentsModel = M('students');
			$condition['stu_id'] = $stu_id;
			$rs = $studentsModel->where($condition)->join('cm_school_time ON cm_students.classes_name = cm_school_time.classes_name')->order('subject_name,week_record,time_interval')->select();
			if($rs!=null){
				//开始处理课表
				$subject_name = $rs[0]['subject_name'];		//获取第一行数据，因为后面都是一样的
				$room_name = $rs[0]['room_name'];
				$teacher_name = $rs[0]['teacher_name'];
				$week_day = $rs[0]['week_day'];
				$week_record = array();
				$time_interval = array();
				$i = 0;
				$w = 0;
				foreach($rs as $key=>$val){
					if($val['subject_name']==$subject_name){
						// echo $w.' '.$key.'<br/>';
						$week_record[$w] = $val['week_record'];			//处理周数
						$time_interval[$w] = $val['time_interval'];		//处理时间段
						$w++;
					}else{
						//处理一门课程
						$week_record = array_unique($week_record);
						$time_interval = array_unique($time_interval);
						$res[$i] = array('room_name'=>$room_name,'teacher_name'=>$teacher_name,'week_day'=>$week_day,'subject_name'=>$subject_name,'week_record'=>$week_record,'time_interval'=>$time_interval);
						//var_dump($res);
						$i++;
						//初始化参数
						$subject_name = $val['subject_name'];
						$room_name = $val['room_name'];
						$teacher_name = $val['teacher_name'];
						$week_day = $val['week_day'];
						unset($week_record);		//清空数组
						unset($time_interval);
						$w = 0;
						$week_record[$w] = $val['week_record'];
						$time_interval[$w] = $val['time_interval'];
						// echo $w.' '.$key.'<br/>';
						$w++;
					}
				}
				$week_record = array_unique($week_record);			//处理最后一次
				$time_interval = array_unique($time_interval);
				$res[$i] = array('room_name'=>$room_name,'teacher_name'=>$teacher_name,'week_day'=>$week_day,'subject_name'=>$subject_name,'week_record'=>$week_record,'time_interval'=>$time_interval);
				// 处理周次和时间段
				foreach($res as $key=>$val){
					$week_record = $val['week_record'];
					$first = current($week_record);
					$end = end($week_record);
					$res[$key]['week_record'] = $first.'-'.$end;
					$time_interval = $val['time_interval'];
					$first = current($time_interval);
					$end = end($time_interval);
					$res[$key]['time_interval'] = $first.'-'.$end;
				}
				// var_dump($week);
				$ages = array();
				foreach ($res as $val) {
				  $ages[] = $val['week_day'];
				}
				array_multisort($ages, SORT_ASC, $res);		//将数组按天数进行排序
				$a1 = 0;$a2 = 0;$a3 = 0;$a4 = 0;$a5 = 0;
				foreach($res as $key=>$val){							//相相同的天数的数组合并
					if($val['week_day'] == 1){
						$tempRes1[$a1] = $res[$key];
						$a1++;
					}
					else if($val['week_day'] ==2 ){
						$tempRes2[$a2] = $res[$key];
						$a2++;
					}
					else if($val['week_day'] ==3 ){
						$tempRes3[$a3] = $res[$key];
						$a3++;
					}
					else if($val['week_day'] ==4 ){
						$tempRes4[$a4] = $res[$key];
						$a4++;
					}
					else if($val['week_day'] ==5 ){
						$tempRes5[$a5] = $res[$key];
						$a5++;
					}
				}
				$count = count($tempRes1)+count($tempRes2)+count($tempRes3)+count($tempRes4)+count($tempRes5);
				$res = array('one'=>$tempRes1,'two'=>$tempRes2,'three'=>$tempRes3,'four'=>$tempRes4,'five'=>$tempRes5);
				$res = array_filter($res);			//过滤下res数组
				$ret3 = array('stu_id'=>$rs[0]['stu_id'],'stu_no'=>$rs[0]['stu_no'],'stu_name'=>$rs[0]['stu_name'],'stu_major'=>$rs[0]['stu_major'],'classes_name'=>$rs[0]['classes_name'],'term_name'=>substr($rs[0]['term'],0,30),'stu_subject'=>$res,'subject_num'=>$count);
				$ret = array('ret'=>$ret3,'status'=>'success');
			}
		}
		echo json_encode($ret);
	}
	
	//查看课程调课情况
	public function getMessage(){
		$ret = array('res'=>array('mes'=>null,'mes_num'=>0),'status'=>'lose');
		$stu_id = I('get.stu_id','');
		if($stu_id != ''){
			$rs = M('student')->join('cm_classes ON cm_student.classes_id = cm_classes.classes_id')->find($stu_id);
			$classes_name = $rs['classes_name'];
			// var_dump($classes_name);
			$condition['is_allow'] = 1;
			$condition['classes_name'] =  array('like',"%$classes_name%");
			$rs = M('teacherSchedules')->field('term_name,subject_name,tea_name,time,week_day,room_name,week_recordB,week_recordA,week_dayA,time_interval,room_name_new')->where($condition)->join('cm_tea_changeschedule ON cm_teacher_schedules.tea_sch_id = cm_tea_changeschedule.tea_sch_id')->join('cm_term ON cm_teacher_schedules.term_id = cm_term.term_id')->join('cm_teacher ON cm_teacher_schedules.tea_no = cm_teacher.tea_no')->order('apply_date desc')->select();
			$ret = array('res'=>array('mes'=>$rs,'mes_num'=>count($rs)),'status'=>'success');
		}
		echo json_encode($ret);
	}
	
}
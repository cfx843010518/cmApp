<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class HomeAdminController extends Controller {
    public function index(){
	$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
	echo '这是Home模块';
	$this->display('test');
	}
	
	//用于测试
	public function test(){
		$this->display('test');
	}
	
	//管理者查看学生的申请情况
	public function watchStudentApply(){
		$ret = array('res'=>null,'status'=>'lose');
		$home_admin_id = I('get.home_admin_id');
		if($home_admin_id!=''){
			// $condition['active_type'] = 1;
			$condition['is_approve'] = 0;
			// $rs = M('roomApply')->where($condition)->select();
			$rs = M('roomApply')->field('user_id,room_apply_id,active_type,active_theme,time_interval,week_record,week_day,sponsor,apply_reason,room_name,is_share,apply_date,is_approve,approve_name')->where($condition)->select();
			//把得到的用户id去查询具体身份
			foreach($rs as $key=>$val){
				$active_type = $val['active_type'];
				if($active_type==1){
					$temp = M('student')->find($val['user_id']);
					$rs[$key]['_no'] = $temp['stu_no'];
					$rs[$key]['_name'] = $temp['stu_name'];
				}else{
					$temp = M('teacher')->find($val['user_id']);
					$rs[$key]['_no'] = $temp['tea_no'];
					$rs[$key]['_name'] = $temp['tea_name'];
				}
			}
			$res = array('mes'=>$rs,'mes_num'=>count($rs));
			$ret = array('res'=>$res,'status'=>'success');
		}
		echo json_encode($ret);
	}
	
	//管理者查看教师的申请情况
	public function watchTeacherApply(){
		$ret = array('res'=>null,'status'=>'lose');
		$home_admin_id = I('get.home_admin_id');
		if($home_admin_id!=''){
			$condition['active_type'] = 2;
			$condition['is_approve'] = 0;
			$rs = M('roomApply')->field('room_apply_id,active_type,active_theme,time_interval,week_record,week_day,sponsor,apply_reason,room_name,is_share,apply_date,is_approve,approve_name,tea_no,tea_name')->where($condition)->join('cm_teacher ON cm_room_apply.user_id = cm_teacher.tea_id')->select();
			$res = array('mes'=>$rs,'mes_num'=>count($rs));
			$ret = array('res'=>$res,'status'=>'success');
		}
		echo json_encode($ret);
	}
	
	//查看教师调课情况
	public function watchTeacherChangeSch(){
		$ret = array('res'=>null,'status'=>'lose');
		$condition['is_allow'] = 0;
		$rs = M('teacherSchedules')->field('cm_teacher.tea_no,tea_name,subject_name,time,week_day,room_name,classes_name,week_recordB,tea_changeSchedule_id,week_recordA,week_dayA,time_interval,apply_date,room_name_new,is_allow,allow_man,term_name')->where($condition)->join('cm_tea_changeschedule ON cm_teacher_schedules.tea_sch_id = cm_tea_changeschedule.tea_sch_id')->join('cm_teacher ON cm_teacher_schedules.tea_no = cm_teacher.tea_no')->join('cm_term ON cm_teacher_schedules.term_id = cm_term.term_id')->order('apply_date desc')->select();
		// var_dump($rs);
		if($rs!=null){
			$ret = array('res'=>$rs,'status'=>'success','mes_num'=>count($rs));
		}
		echo json_encode($ret);
	}
	
	//是否同意教师调课
	public function allowTeacherChangeSch(){
		$ret = array('status'=>'lose');
		$home_admin_id = I('get.home_admin_id');
		if($home_admin_id!=''){
			$rs = M('homeAdmin')->find($home_admin_id);
			$home_admin_name = $rs['home_admin_name'];
			$tea_changeSchedule_id = I('get.tea_changeSchedule_id');
			$is_allow = I('get.is_allow');
			$teaChangescheduleModel = M('teaChangeschedule');
			$teaChangescheduleModel->find($tea_changeSchedule_id);
			//调课被否决
			if($is_allow==-1){	
				$condition['room_name'] = $teaChangescheduleModel->room_name_new;
				$condition['week_record'] = $teaChangescheduleModel->week_recordA;
				$condition['week_day'] = $teaChangescheduleModel->week_dayA;
				$time_interval = $teaChangescheduleModel->time_interval;
				if(strlen($time_interval)==1){					//只有一节课的情况，有点特殊
					$condition['time_interval'] = array(in,array($time_interval));
				}
				else{
				//两节课以上的情况
					$time_interval = explode('-',$time_interval);
					for($i=1;$i<=$time_interval[1];$i++){
						$a[] = $i;
					}
					$condition['time_interval'] = array(in,$a);
				}
				$data['room_status'] = 2;
				$data['classes_name'] = null;
				$data['subject_name'] = null;
				$data['teacher_name'] = null;
				M('schoolTime')->where($condition)->save($data);			//将课室设置为空
				$teaChangescheduleModel->is_allow = -1;
			}
			//调课被同意
			else{
				$teaChangescheduleModel->is_allow = 1;
			}
			$teaChangescheduleModel->allow_man = $home_admin_name;
			$result = $teaChangescheduleModel->save();								//记录下申请人
			if($result){
				$ret = array('status'=>'success');
			}
		}
		echo json_encode($ret);
	}
	
	
	
	
	//是否同意申请活动
	public function allowApply(){
		//同意教室申请
		$ret = array('status'=>'lose');
		$room_apply_id = I('get.room_apply_id','');
		if($room_apply_id != ''){
			$model = M();
			$model->startTrans();		//开启事物
			$roomApplyModel = M('roomApply');
			$roomApplyModel->find($room_apply_id);
			$week_record = $roomApplyModel->week_record;
			$week_day = $roomApplyModel->week_day;
			$room_name = $roomApplyModel->room_name;
			$time_interval = explode('-',$roomApplyModel->time_interval);
			$schoolTime = M('schoolTime');
			$condition['week_record'] = $week_record;
			$condition['week_day'] = $week_day;
			$condition['room_name'] = $room_name;
			$condition['time_interval'] = array('in',$time_interval);
			$home_admin_id = I('get.home_admin_id');
			$is_allow = I('get.is_allow');	//是否同意
			$r = M('homeAdmin')->find($home_admin_id);
			$home_admin_name = $r['home_admin_name'];
			if($is_allow == -1){						//不同意申请
				// $roomApplyModel->startTrans();	
				$roomApplyModel->is_approve = -1;
				$roomApplyModel->approve_name = $home_admin_name;		//附上审批人
				$result = $roomApplyModel->save();
				$rs = $schoolTime->where($condition)->select();		
				$i=0;
				foreach($rs as $key=>$val){							//把之前设为不空的教室重新设置为空
					$i++;
					$rs[$key]['room_status'] = 2;
					$schoolTime->save($rs[$key]);
				}
				if($result==1&&$i==count($rs)){
					$model->commit();
					$ret = array('status'=>'success');
				}
			}else{
				$rs = $schoolTime->where($condition)->select();
				// var_dump($rs);
				$roomApplyModel->is_approve = 1;
				$roomApplyModel->approve_name = $home_admin_name;
				$result = $roomApplyModel->save();
				// echo $result;
				if($result==1){
					$ret = array('status'=>'success');
					$model->commit();
				}else{
					$model->rollback();
				}
			}
			
		}
		echo json_encode($ret);
	}
	
	
	
}
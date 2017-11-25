<?php
namespace Home\Model;
use Think\Model;
class TeacherModel extends model{
	
	//获得一个教师的课表信息
	public function getMySch($tea_id){
		$condition['tea_id'] = $tea_id;
		//$query = $this->join('cm_classes ON cm_record_subject.classes_id = cm_classes.classes_id')->join('cm_student ON cm_classes.classes_id = cm_student.classes_id')->select();
		// $query = $this->where($condition)->join('cm_classes ON cm_student.classes_id = cm_classes.classes_id')->join('cm_student_schedules ON cm_classes.classes_id = cm_student_schedules.classes_id')->join('cm_room ON cm_record_subject.room_id = cm_room.room_id')->select();
		$query = $this->where($condition)->join('cm_teacher_schedules ON cm_teacher.tea_no = cm_teacher_schedules.tea_no')->order('week_day')->select();
		// $query = $this->select();
		return $query;
	}
	
	public function changeMyClasses(){
		
	}
}
?>

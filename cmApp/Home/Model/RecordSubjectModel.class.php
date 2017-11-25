<?php
namespace Home\Model;
use Think\Model;
class RecordSubjectModel extends model{
	
	//获得一个学生的课表信息
	public function getStuSubjectTable($stu_id){
		$query = $this->join('cm_classes ON cm_record_subject.classes_id = cm_classes.classes_id')->join('cm_student ON cm_classes.classes_id = cm_student.classes_id')->select();
		return $query;
	}
}
?>

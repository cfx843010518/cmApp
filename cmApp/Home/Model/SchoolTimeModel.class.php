<?php
namespace Home\Model;
use Think\Model;
class SchoolTimeModel extends model{
	
	//查询不在教师使用情况表里面的房间
	public function getSearchRoom($room_type_id,$week_day,$time_interval,$week_record){
		$condition = array();
		// if($room_type_id != ''){
			// $condition['room_type_id'] = $room_type_id;
		// }
		$condition['room_type_id'] = $room_type_id;
		$condition['room_status'] = 2;
		if($week_day != ''){
			$condition['week_day'] = $week_day;
		}
		if($week_record != ''){
			$condition['week_record'] = $week_record;	
		}
		if($time_interval != ''){
			$time_interval = explode('-',$time_interval);
			// var_dump($time_interval);
			for($i=$time_interval[0];$i<=$time_interval[1];$i++){
				$ret[] = $i;
			}
			// var_dump($ret);
			$condition['time_interval'] = array('in',$ret);
		}
		// $rs = $this->where($condition)->join('RIGHT JOIN cm_room ON cm_room_use.room_id = cm_room.room_id')->select();		//查出符合周次的结果
		if($week_day ==''){
			$query = $this->where($condition)->join('cm_room ON cm_school_time.room_name = cm_room.room_name')->order('week_day')->select();
		}
		else{
			$query = $this->where($condition)->join('cm_room ON cm_school_time.room_name = cm_room.room_name')->select();	
		}
		return $query;
	}
	
	public function getSearchRoom2($room_type_id,$week_day,$time_interval,$week_record){
		$condition = array();
		// if($room_type_id != ''){
			// $condition['room_type_id'] = $room_type_id;
		// }
		$condition['room_type_id'] = $room_type_id;
		$condition['room_status'] = 2;
		if($week_day != ''){
			$condition['week_day'] = $week_day;
		}
		if($week_record != ''){
			$condition['week_record'] = $week_record;	
		}
		if($time_interval != ''){
			$time_interval = explode('-',$time_interval);
			// var_dump($time_interval);
			for($i=$time_interval[0];$i<=$time_interval[1];$i++){
				$ret[] = $i;
			}
			// var_dump($ret);
			$condition['time_interval'] = array('in',$ret);
		}
		// $rs = $this->where($condition)->join('RIGHT JOIN cm_room ON cm_room_use.room_id = cm_room.room_id')->select();		//查出符合周次的结果
		$query = $this->where($condition)->join('cm_room ON cm_school_time.room_name = cm_room.room_name')->order('cm_room.room_name,week_day,time_interval')->select();
		return $query;
	}
	
	
	
	
	
	
	
	
	// public function getSearchRoom($room_name,$week_record){
		// if($room_name!=''){
			// $condition['room_name'] = $room_name;
		// }
		// if($week_record != ''){
			// $condition['week_record'] = $week_record;
		// }
		// $condition['room_status'] = '空闲';
		// $query = $this->where($condition)->select();
		// return $query;
	// }
}
?>

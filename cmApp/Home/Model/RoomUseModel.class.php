<?php
namespace Home\Model;
use Think\Model;
class RoomUseModel extends model{
	
	//查询不在教师使用情况表里面的房间
	public function getSearchRoom($room_type_id,$week_day,$time_interval_id,$week_record){
		$condition = array();
		if($week_day != ''){
			$condition['week_day'] = $week_day;
		}
		if($room_type_id != ''){
			$condition['room_type_id'] = $room_type_id;
		}
		if($week_record != ''){
			$condition['week_record'] = array('like','%'.$week_record.'%');		//使用模糊查询查询周数
		}
		// $rs = $this->where($condition)->join('RIGHT JOIN cm_room ON cm_room_use.room_id = cm_room.room_id')->select();		//查出符合周次的结果
		$rs = $this->query("select * from cm_room");
		var_dump($rs);
		// if($rs!=null){									//不等于空说明查到了数据，要进行反向查询
			// foreach($rs as $key=>$val){					//获得已查询到的课室使用id，进行反向查询
				// $array[$key] = $val['room_use_id'];
			// }
			//var_dump($array);
			// $condition2['room_use_id'] = array('not in',$array);
			// if($room_type_id != ''){
				// $condition2['room_type_id'] = $room_type_id;
			// }
		// }
		// $rs1 = $this->where($condition2)->join('Right JOIN cm_room ON cm_room_use.room_id = cm_room.room_id')->select();
		// if($week_record !=''){
			// $res = array();
			// foreach($rs as $key=>$val){
				// $array1 = explode('-',$val['week_record']);
				// var_dump($array1);
				// $array2 = explode('-',$val['time_interval']);
				// if((!($week_record<$array1[0])||($week_record1>$array1[1]&&$week_record2>$array1[1]))) && !(($time_interval_array[0]<$array2[0]&&$time_interval_array[1]<$array2[0])||($time_interval_array[0]>$array2[1]&&$time_interval_array[1]>$array2[1]))){
					// $res[$key] = $val;
				// }
				// if(!($week_record<$array1[0]||$week_record>$array1[1])){
					// $res[$key] = $val;
				// }
			// }
			// $rs = $res;
		// }
		return $rs1;
	}
}
?>

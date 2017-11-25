<?php
namespace Home\Model;
use Think\Model;
class RoomModel extends model{
	//²éÑ¯¿ÎÊÒÇé¿ö
	public function getRoomFree($room_type_id,$week_day,$time_interval_id,$week_record){
		$condition = array();
		if($week_day != ""){
			$condition['week_day'] = $week_day;
		}
		$query = $this->where($condition)->join('cm_room_use ON cm_room.room_id = cm_room_use.room_id')->select();
		//$query = $this->select();
		// $query = $this->query("select * from cm_room left join cm_room_use ON cm_room.room_id = cm_room_use.room_use_id");
		return $query;
	}
	
}
?>





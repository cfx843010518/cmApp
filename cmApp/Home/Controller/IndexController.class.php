<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		$this->export('S3316.xlsx');
	}
	
	public function export($load){
		require_once 'module/PHPExcel/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = \PHPExcel_IOFactory::load("Public/upload/".$load);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet0=$objPHPExcel->getSheet(0);
		$rowCount=$sheet0->getHighestRow();//  			获取excel行数
		$data=array();
		$room_type = $objPHPExcel->getActiveSheet()->getCell("A1")->getValue();
		$room_type = substr($room_type,0,9);		//获得教室类型
		if($room_type=='实训楼'){
			$room_type_id = 1;
		}else{
			$room_type_id = 2;
		}
		$term =  $objPHPExcel->getActiveSheet()->getCell("H2")->getValue();  //获取学期
		$one =  $objPHPExcel->getActiveSheet()->getCell("A2")->getValue();
		//echo $term;
		
		$room_name = substr($one,15,5);		//获得教室编号
		$room_volume = substr($one,34,5);		//获得教室人数
		//往学期表插入一条记录
		// $data['term_name'] = $term;
		// M('term')->add($data);
		// 往课室表插入一条记录
		// $data2['room_name'] = $room_name;
		// $data2['room_type_id'] = $room_type_id;
		// $data2['room_volume'] = $room_volume;
		// M('room')->add($data2);
		for ($i = 4; $i <= $rowCount-1; $i++){	//循环导入信息	
			$str = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
			$this->run($str,$i,$room_name,'C');
			$str = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
			$this->run($str,$i,$room_name,'D');
			$str = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();	
			$this->run($str,$i,$room_name,'E');
			$str = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();	
			$this->run($str,$i,$room_name,'F');
			$str = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();	
			$this->run($str,$i,$room_name,'G');
			$str = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();	
			$this->run($str,$i,$room_name,'H');
			$str = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();	
			$this->run($str,$i,$room_name,'I');
		}
		// $str = $objPHPExcel->getActiveSheet()->getCell("F5")->getValue();
		// $this->run($str,5,$room_name,'F');
		// $str = $objPHPExcel->getActiveSheet()->getCell("H4")->getValue();
		// if($str == ''){
			// $column = $this->judgeColumn(4);
			// var_dump($column);
			// $this->getRow2($room_name,$column);
			
		// }else{
			
		// }
		
		// $str = $objPHPExcel->getActiveSheet()->getCell("H4")->getValue();
		
		// $this->getRow($str,$room_name);
		// var_dump($sheet0);
	}
	
	public function run($str,$i,$room_name,$judge){
		if($str == ''){
			$column = $this->judgeColumn($i);
			$week_day = $this->judgeTime($judge);
			// var_dump($column);
			$this->getRow2($room_name,$column,$week_day);
		}else{
			$week_day = $this->judgeTime($judge);
			$this->getRow($str,$room_name,$week_day);
		}
	}
	
	//判断是哪时间段
	public function judgeColumn($column){
		if($column == 4){
			return array(1,2);
		}
		else if($column == 5){
			return array(3,4);
		}
		else if($column == 6){
			return array(5,6);
		}
		else if($column==7){
			return array(7,8);
		}
		else if($column==8){
			return array(9,10);
		}
		else if($column==9){
			return array(11,12);
		}
	}
	
	public function judgeTime($judge){
		//判断星期
		if($judge=='C'){
			return 1;
		}else if($judge=='D'){
			return 2;
		}else if($judge=='E')
		{
			return 3;
		}else if($judge=='F')
		{
			return 4;
		}else if($judge=='G')
		{
			return 5;
		}else if($judge=='H')
		{
			return 6;
		}else if($judge=='I'){
			return 7;
		}
	}
	//将获得的数据导入数据库
	public function getRow($str,$room_name,$week_day){
		$array = explode('◇',$str);
		$teacher_nameA = explode('【',$array[3]);
		$teacher_name = $teacher_nameA[0];											//获得教师名称
		// var_dump($array);
		$tempStr = $array[1];
		$tempStr = explode(')',$tempStr);
		$tempStr = explode('(',$tempStr[0]);
		$week = explode('-',$tempStr[0]);		//获得周次
		$time_interval = explode(',',$tempStr[1]);	//获得时间段
		for($k=0;$k<2;$k++){
			for($j=$week[0];$j<=$week[1];$j++){
				if(isset($time_interval[$k])){
					$data['room_status'] = '1';
					$data['classes_name'] = $array[2];
					$data['subject_name'] = $array[0];
					$data['teacher_name'] = $teacher_name;
					$data['time_interval'] = $time_interval[$k];
				}else{
					$data['time_interval'] = $time_interval[$k-1]+1;
					$data['room_status'] = '2';
				}
				$data['term'] = '1';
				$data['room_name'] = $room_name;
				$data['week_record'] = $j;
				$data['week_day'] = $week_day;
				M('schoolTimes')->add($data);
				unset($data);		//初始化参数
			}
		}
	}
	
	//空白的处理掉
	public function getRow2($room_name,$time_interval,$week_day){
		for($k=$time_interval[0];$k<=$time_interval[1];$k++){
			for($j=1;$j<=18;$j++){
				$data['room_status'] = '2';
				$data['term'] = '1';
				$data['room_name'] = $room_name;
				$data['week_record'] = $j;
				$data['week_day'] = $week_day;
				$data['time_interval'] = $k;
				M('schoolTimes')->add($data);
				unset($data);		//初始化参数
			}
		}
	}
	
	
}
?>
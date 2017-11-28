<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
use Org\Util\MyUnit;
class AdminController extends Controller {
    public $term;
	public $myWeek = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'',15=>'',16=>'',17=>'',18=>'');    //定义一个全局变量
	//进入登录页面
	public function index(){
		$this->display('login');
		
	}
	//修改管理员密码显示
	public function updatePwd(){
		$this->display('updateAdminPwd');
	}
	//修改密码
	public function updatePwdAction(){
		$ret = -1;
		$a_account = I('post.a_account','');
		if($a_account != ''){
			$a_password = I('post.a_password');
			$condition['a_account'] = $a_account;
			$adminModel = M('admin');
			$rs = $adminModel->where($condition)->find();
			if($rs!=null){
				$rs['a_password'] = md5($a_password);
				$adminModel->save($rs);
				$ret = 1;
			}
		}
		echo $ret;
	}
	
	/*教室信息的录入界面*/
	public function inputRoomMes(){
	// $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
	// echo '这是Home模块';
	
		$this->display('inputRoomMes');
	}
	//单个教室信息的录入
	public function exportRoomMes(){
		if (!empty($_FILES)) {
            $filename = 'a.xls';
			$tmp_name = $_FILES['file']['tmp_name'];
			move_uploaded_file($tmp_name,'Public/temp/'.$filename);
			$this->export1('temp/'.$filename);
		}
		// $this->success('导入成功','init');
	}
	// 多个教室信息的录入
	public function exprotExcelMany(){
		//清空一下该目录文件
		$this->deleteAllFile(BASE_PATH.'Public/upload1/');
		//上传文件，解压文件
		$filename = 'a.zip';
		$tmp_name = $_FILES['file2']['tmp_name'];
		move_uploaded_file($tmp_name,'Public/temp/'.$filename);
		$zip = new MyUnit();			//实例化zip工具类
		$zipfile   = BASE_PATH.'Public/temp/'.$filename;				//压缩文件名
		$savepath  = BASE_PATH.'Public/upload1/';						//解压缩目录名
		$array = $zip->GetZipInnerFilesInfo($zipfile);
		// var_dump($array);
		$filecount = 0;
        $dircount  = 0;
        $failfiles = array();
		for($i=0; $i<count($array); $i++) {
            if($array[$i]['folder'] == 0){
                if($zip->unZip($zipfile, $savepath, $i) > 0){
                    $filecount++;
                }else{
                    $failfiles[] = $array[$i]['filename'];
                }
            }else{
                $dircount++;
            }
        }
		// Open a known directory, and proceed to read its contents
		$dir = "./Public/upload1/";
		$file=scandir($dir);
		$model = M();
		$model->startTrans();
		for($i=2;$i<count($file);$i++){
			$this->export1('upload1/'.$file[$i]);									//遍历文件，实现多导入
			if($i%20==0){
				$model->commit();		//每隔10次提交
				$model->startTrans();
			}
		}
		//最后一次提交事物
		$model->commit();
		$this->success('导入成功','init');
	}
	//Excel表的导入函数
	public function export1($load){
		require_once 'module/PHPExcel/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = \PHPExcel_IOFactory::load("Public/".$load);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet0=$objPHPExcel->getSheet(0);
		$rowCount=$sheet0->getHighestRow();				//获取excel行数
		
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
		//插入学期表之前先查询
		$condition['term_name'] = $term;
		$rs = M('term')->where($condition)->find();
		if($rs==''){
			//往学期表插入一条记录
			$data['term_name'] = $term;
			$term_id = M('term')->add($data);
		}else{
			$term_id = $rs['term_id'];
		}
		// 往课室表插入一条记录
		$data2['room_name'] = $room_name;
		$data2['room_type_id'] = $room_type_id;
		if($room_volume){
			$data2['room_volume'] = $room_volume;
		}else{
			$data2['room_volume'] = 100;
		}
		M('room')->add($data2);			//往教室表插入一条数据
		$rowCount = $rowCount/2;
		for ($i = 4; $i <= $rowCount-1; $i++){	//循环导入信息	
			$str = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
			$this->run($str,$i,$room_name,'C',$term_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
			$this->run($str,$i,$room_name,'D',$term_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();	
			$this->run($str,$i,$room_name,'E',$term_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();	
			$this->run($str,$i,$room_name,'F',$term_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();	
			$this->run($str,$i,$room_name,'G',$term_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();	
			$this->run($str,$i,$room_name,'H',$term_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();	
			$this->run($str,$i,$room_name,'I',$term_id);
			// ob_flush();
			// flush();
		}
		// $str = $objPHPExcel->getActiveSheet()->getCell("D6")->getValue();
		// var_dump($str);
		// $str = explode("\n",$str);				//记住单引号要写成双引号，不然无法分割
		// var_dump($str);
		// $this->run($str,6,$room_name,"D",$term_id);
	}
	//run函数
	public function run($str,$i,$room_name,$judge,$term_id){
		$column = $this->judgeColumn($i);
		$week_day = $this->judgeTime($judge);
		if($str == ''){	
			// var_dump($column);
			$this->getRow2($room_name,$column,$week_day,$term_id,1,18);
		}
		else{
			$this->getRow($str,$room_name,$week_day,$term_id,$column);
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
	public function getRow($str,$room_name,$week_day,$term_id,$column){
		//处理两行的情况
		$str = explode("\n",$str);
		foreach($str as $key=>$val){
			$index = strpos($val,"借用");
			if($index){				//如果找到
				// $brough = substr($val,0,$index);
				// var_dump($brough);
			}
			else{
				//没有找到
				$this->explodes($val,$room_name,$week_day,$term_id,$column);
			}
			
		}
		$array = $this->myWeek;
		// var_dump($array);
		// var_dump($column);
		// 处理剩下的星期
		for($k=$column[0];$k<=$column[1];$k++){
			foreach($array as $key=>$val){
				$data['room_status'] = '2';
				$data['term_id'] = $term_id;
				$data['room_name'] = $room_name;
				$data['week_record'] = $key;
				$data['week_day'] = $week_day;
				$data['time_interval'] = $k;
				M('schoolTime')->add($data);
				unset($data);		//初始化参数
			}
		}
		// 初始化变量
		$this->myWeek = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'',15=>'',16=>'',17=>'',18=>'');    //定义一个全局变量	
	}
	
	public function explodes($str,$room_name,$week_day,$term_id,$column){
		$array = explode('◇',$str);
		// var_dump($array);
		$teacher_nameA = explode('【',$array[3]);
		$teacher_name = $teacher_nameA[0];											//获得教师名称
		$tempStr = $array[1];
		if(strpos($tempStr,"/")){
			$temp = explode('(',$tempStr);
			$temp = explode(')',$temp[1]);
			// var_dump($temp);
			$weeks = explode('-',$temp[0]);
			// var_dump($weeks);
			foreach($weeks as $key=>$val){			//去掉前缀零
				if(!substr($val,0,1)){
					$weeks[$key] = substr($val,1,1);
				}
			}
			$a = $weeks[0].'-'.$weeks[1];
			$week[] = $a;
			$time_interval = explode("节",$temp[1]);
			$time_interval = explode("[",$time_interval[0]);
			// var_dump($time_interval);
			$time_interval = explode('-',$time_interval[1]);
			// var_dump($time_interval);
		}
		else{
			$tempStr = explode(')',$tempStr);
			$tempStr = explode('(',$tempStr[0]);
			$week = explode(',',$tempStr[0]);
			$time_interval = explode(',',$tempStr[1]);	//获得时间段
		}
		foreach($week as $key=>$val){
			$weeks = explode('-',$val);				//获得周次
			// var_dump($weeks);
			// var_dump($time_interval);
			for($k=0;$k<2;$k++){
				for($j=$weeks[0];$j<=(isset($weeks[1])? $weeks[1]:$weeks[0]);$j++){
					// 去掉有的星期
					$myArray = $this->myWeek;
					unset($myArray[$j]);
					$this->myWeek = $myArray;
					if(isset($time_interval[$k])){
						$data['room_status'] = '1';
						$data['classes_name'] = $array[2];
						$data['subject_name'] = $array[0];
						$data['teacher_name'] = $teacher_name;
						$data['time_interval'] = $time_interval[$k];
					}else{
						if($column[0]==$time_interval[0]){
							$data['time_interval'] = $time_interval[$k-1]+1;
						}
						else{
							$data['time_interval'] = $time_interval[$k-1]-1;
						}
						$data['room_status'] = '2';
					}
					$data['term_id'] = $term_id;
					$data['room_name'] = $room_name;
					$data['week_record'] = $j;
					$data['week_day'] = $week_day;
					M('schoolTime')->add($data);
					unset($data);		//初始化参数
				}
			}
		}	
	}
	
	//处理课室借用的情况
	
	
	//空白的处理掉
	public function getRow2($room_name,$time_interval,$week_day,$term_id,$j_init,$j_max){
		for($k=$time_interval[0];$k<=$time_interval[1];$k++){
			for($j=$j_init;$j<=$j_max;$j++){
				$data['room_status'] = '2';
				$data['term_id'] = $term_id;
				$data['room_name'] = $room_name;
				$data['week_record'] = $j;
				$data['week_day'] = $week_day;
				$data['time_interval'] = $k;
				M('schoolTime')->add($data);
				unset($data);		//初始化参数
			}
		}
	}
	/*教室信息的录入*/
	
	
	/*学生课表信息的录入*/
	public function inputStuSubjectMes(){
	
		$this->display('inputStuSubjectMes');
	}
	//单个课表信息的录入
	public function exportStuSubMes(){
		if (!empty($_FILES)) {
            $filename = 'a.xls';
			$tmp_name = $_FILES['file']['tmp_name'];
			move_uploaded_file($tmp_name,'Public/temp/'.$filename);
			// echo $filename;
			$this->export2('temp/'.$filename);
			// $this->success('导入成功','Mains');
		}
		// $this->export2('班级名称：15ERP1(59人)    班级代码：01051501.xls');
		$this->success('导入成功','init');
	}
	// 多个课表信息的录入
	public function exprotManyStuSub(){
		$this->deleteAllFile(BASE_PATH.'Public/upload2/');
		$filename = 'b.zip';
		$tmp_name = $_FILES['file2']['tmp_name'];
		move_uploaded_file($tmp_name,'Public/temp/'.$filename);
		$zip = new MyUnit();			//实例化zip工具类
		$zipfile   = BASE_PATH.'Public/temp/'.$filename;				//压缩文件名
		$savepath  = BASE_PATH.'Public/upload2/';						//解压缩目录名
		$array = $zip->GetZipInnerFilesInfo($zipfile);
		// var_dump($array);
		$filecount = 0;
        $dircount  = 0;
        $failfiles = array();
		for($i=0; $i<count($array); $i++) {
            if($array[$i]['folder'] == 0){
                if($zip->unZip($zipfile, $savepath, $i) > 0){
                    $filecount++;
                }else{
                    $failfiles[] = $array[$i]['filename'];
                }
            }else{
                $dircount++;
            }
        }
		
		// Open a known directory, and proceed to read its contents
		$dir = "./Public/upload2/";
		$file=scandir($dir);
		for($i=2;$i<count($file);$i++){
			$this->export2('upload2/'.$file[$i]);									//遍历文件，实现多导入
		}
		$this->success('导入成功','init');
	}
	//Excel表的导入函数
	public function export2($load){
		require_once 'module/PHPExcel/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = \PHPExcel_IOFactory::load("Public/".$load);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet0=$objPHPExcel->getSheet(0);
		$rowCount=$sheet0->getHighestRow();//  			获取excel行数
		$columnCount=$sheet0->getHighestColumn();		//获取excel列数
		$columnCount= \PHPExcel_Cell::columnIndexFromString($columnCount); //字母列转换为数字列如
		// var_dump($columnCount);
		$data=array();
		$term_name = $objPHPExcel->getActiveSheet()->getCell("H2")->getValue();	//获取学期
		if($term_name==''){
			$term_name = $objPHPExcel->getActiveSheet()->getCell("F2")->getValue();	//获取学期
		}
		// echo $term_name;
		$condition['term_name'] = $term_name;
		$rs = M('term')->where($condition)->find();
		if($rs==null){
			$data['term_name'] = $term_name;
			$term_id = M('term')->add($data);
		}else{
			$term_id = $rs['term_id'];		//获得学期编号
		}			
		// 开始插入数据
		$A2 = $objPHPExcel->getActiveSheet()->getCell("A2")->getValue();
		$a2 = explode('(',$A2);
		// var_dump($a2);
		if(count($a2)>2){
			$a3 = explode(')',$a2[1]);
			$classes_name = explode('：',$a2[0]);
			$classes_name = $classes_name[1].'('.$a3[0].')';			//获得班级名称   班级名称：15软件2(JAVA)(57人)    班级代码：01041502
			$temp = explode(')',$a2[2]);
			preg_match_all('/\d+/',$temp[0],$arr);
			$arr = join('',$arr[0]);
			$classes_num = $arr;						//获得班级人数     
		}
		else{
			$classes_name = explode('：',$a2[0]);		//获得班级名称    班级名称：15ERP1(59人)    班级代码：01051501
			$classes_name = $classes_name[1];
			$temp = explode(')',$a2[1]);
			preg_match_all('/\d+/',$temp[0],$arr);
			$arr = join('',$arr[0]);
			$classes_num = $arr;				//获得班级人数
		}
		$data['classes_name'] = $classes_name;		//处理数据
		$data['classes_num'] = $classes_num;		
		$classes_id = M('classes')->add($data);					//往班级表插入一条记录,顺便获得classes_id
		// 开始处理课表
		$rowCount = $rowCount/2;
		//var_dump($rowCount);
		for ($i = 4; $i <= $rowCount-1; $i++){	//循环导入信息	
			$str = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
			$this->run2($str,$room_name,'C',$term_id,$classes_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
			$this->run2($str,$room_name,'D',$term_id,$classes_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();	
			$this->run2($str,$room_name,'E',$term_id,$classes_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();	
			$this->run2($str,$room_name,'F',$term_id,$classes_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();	
			$this->run2($str,$room_name,'G',$term_id,$classes_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();	
			$this->run2($str,$room_name,'H',$term_id,$classes_id);
			$str = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();	
			$this->run2($str,$room_name,'I',$term_id,$classes_id);
		}
		// $str = $objPHPExcel->getActiveSheet()->getCell("C4")->getValue();
		// $this->run($str,4,$room_name,"C",$term_id);
	}
	/*导入学生的课表*/
	public function run2($str,$room_name,$week,$term_id,$classes_id){
		if($str!=''){
			$str = explode("\n",$str);
			foreach($str as $key=>$val){
				$week_day = $this->judgeTime($week);						//获得星期数
				// var_dump($term_name);
				$tempStr = explode('◇',$val);
				$subject_name = $tempStr[0];			//获得课程名字
				$time = $tempStr[1];					//获得时间段
				$room = explode('【',$tempStr[2]);						
				$room_name = substr($room[0],0,9);
				if($room_name=='实训楼'){
					$room_name = 'S'.substr($room[0],10,4);		//获得教室编号
				}else{
					$room_name = 'J'.substr($room[0],10,4);
				}
				$teacher = explode('【',$tempStr[3]);
				$teacher_name = $teacher[0];					//获得老师名称
				$data2['term_id'] = $term_id;
				$data2['classes_id'] = $classes_id;
				$data2['subject_name'] = $subject_name;
				$data2['time'] = $time;
				$data2['tea_name'] = $teacher_name;
				$data2['room_name'] = $room_name;
				$data2['week_day'] = $week_day;
				M('studentSchedules')->add($data2);
			}
			
		}
	}
	

	
	/*教师课表的录入*/
	public function inputTeaSubjectMes(){
	
		$this->display('inputTeacherMes');
	}
	//单个教师课表的录入
	public function exportTeaMes(){
		if (!empty($_FILES)) {
            $filename = 'a.xls';
			$tmp_name = $_FILES['file']['tmp_name'];
			move_uploaded_file($tmp_name,'Public/temp/'.$filename);
			// echo $filename;
			$this->export3('temp/'.$filename);
			// $this->success('导入成功','Mains');
		}
		// $this->export2('班级名称：15ERP1(59人)    班级代码：01051501.xls');
		$this->success('导入成功','init');
	}
	// 多个教师课表的录入
	public function exprotManyTeaSub(){
		$filename = 'c.zip';
		$tmp_name = $_FILES['file2']['tmp_name'];
		move_uploaded_file($tmp_name,'Public/temp/'.$filename);
		$zip = new MyUnit();			//实例化zip工具类
		$zipfile   = BASE_PATH.'Public/temp/'.$filename;				//压缩文件名
		$savepath  = BASE_PATH.'Public/upload3/';						//解压缩目录名
		$array = $zip->GetZipInnerFilesInfo($zipfile);
		// var_dump($array);
		$filecount = 0;
        $dircount  = 0;
        $failfiles = array();
		for($i=0; $i<count($array); $i++) {
            if($array[$i]['folder'] == 0){
                if($zip->unZip($zipfile, $savepath, $i) > 0){
                    $filecount++;
                }else{
                    $failfiles[] = $array[$i]['filename'];
                }
            }else{
                $dircount++;
            }
        }
		// Open a known directory, and proceed to read its contents
		$dir = "./Public/upload3/";
		$file=scandir($dir);
		for($i=2;$i<count($file);$i++){
			$this->export3('upload3/'.$file[$i]);									//遍历文件，实现多导入
		}
		$this->success('导入成功','init');
	}
	//Excel表的导入函数
	public function export3($load){
		require_once 'module/PHPExcel/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = \PHPExcel_IOFactory::load("Public/".$load);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet0=$objPHPExcel->getSheet(0);
		$rowCount=$sheet0->getHighestRow();//  			获取excel行数
		$columnCount=$sheet0->getHighestColumn();		//获取excel列数
		$columnCount= \PHPExcel_Cell::columnIndexFromString($columnCount); //字母列转换为数字列如
		// var_dump($columnCount);
		$data=array();
		$A2 = $objPHPExcel->getActiveSheet()->getCell("A2")->getValue();
		$tempArray = explode('   ',$A2);
		// var_dump($tempArray);
		$tea = explode('：',$tempArray[0]);
		$tea_no = $tea[1];							//获得教师编号
		$tea_name = $objPHPExcel->getActiveSheet()->getCell("A1")->getValue();
		$a = strpos($tea_name,'老师');
		$tea_name = substr($tea_name,0,$a);			//获得教师名称
		$data10['tea_no'] = $tea_no;
		$data10['tea_name'] = $tea_name;
		$data10['tea_password'] = '123';
		$data10['tea_photo'] = '00.png';
		M('teacher')->add($data10);					//往教师表插入一条信息
		// var_dump($tea_name);
		$term_name = $objPHPExcel->getActiveSheet()->getCell("H2")->getValue();			//学期id
		if($term_name==''){										//做下兼容
			$term_name = $objPHPExcel->getActiveSheet()->getCell("F2")->getValue();
		}
		$condition['term_name'] = $term_name;
		$term = M('term')->where($condition)->find();
		$term_id = $term['term_id'];				//获得学期id
		
		// 开始处理教师课表
		for ($i = 4; $i <= $rowCount-1; $i++){	//循环导入信息	
			$str = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
			$this->run3($str,$tea_no,$term_id,'C');
			$str = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
			$this->run3($str,$tea_no,$term_id,'D');
			$str = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();	
			$this->run3($str,$tea_no,$term_id,'E');
			$str = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();	
			$this->run3($str,$tea_no,$term_id,'F');
			$str = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();	
			$this->run3($str,$tea_no,$term_id,'G');
			$str = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();	
			$this->run3($str,$tea_no,$term_id,'H');
			$str = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();	
			$this->run3($str,$tea_no,$term_id,'I');
		}
		// $str = $objPHPExcel->getActiveSheet()->getCell("D9")->getValue();
		// $this->run3($str,$tea_no,$term_id,"D");
	}
	/*导入教师的课表*/
	public function run3($str,$tea_no,$term_id,$w){
		if($str!=''){
			$week_day = $this->judgeTime($w);
			$str = explode("\n",$str);
			foreach($str as $key=>$val){
				$tempArray1 =  explode('◇',$val);
				// var_dump($tempArray1);
				$subject_name = $tempArray1[0];					//获得所教课程名称
				$time = $tempArray1[1];							//获得时间段
				$room_name = $tempArray1[2];					
				$temp = substr($room_name,0,9);
				if($temp=='实训楼'){
					$room_name = 'S'.substr($room_name,9,4);	//获得教室编号
				}else{
					$room_name = 'J'.substr($room_name,9,4);	//获得教室编号
				}
				$classes_name = $tempArray1[3];					//获得所教班级
				//在插入课表的时候进行判断，已有记录的进行合并
				$condition['tea_no'] = $tea_no;
				$condition['subject_name'] = $subject_name;
				// $condition['time'] = $time;
				$condition['week_day'] = $week_day;
				$condition['room_name'] = $room_name;
				$condition['classes_name'] = $classes_name;
				$condition['term_id'] = $term_id;
				$teacherSchedulesModel =  M('teacherSchedules');
				$rs = $teacherSchedulesModel->where($condition)->find();
				// var_dump($rs);
				if($rs!=null){
					$searchTime = $rs['time'];
					$searchTime = explode('/',$searchTime);
					$myTime = '';
					$count = 0;
					for($i=0;$i<count($searchTime);$i++){
						$timeFront = explode('(',$searchTime[$i]);
						$timesFront = explode('(',$time);
						if($timeFront[0] == $timesFront[0]){
							// echo '来到这里没有';
							$timeBack = $timeFront[1];
							$timeBack = explode(')',$timeBack);
							$timeBack = $timeBack[0];
							$timesBack = $timesFront[1];
							$timesBack = explode(')',$timesBack);
							$timesBack = $timesBack[0];
							// var_dump($timesBack);
							if($i==0){
								$myTime .= $timeFront[0].'('.$timeBack.','.$timesBack.')';
							}
							else{
								$myTime .= '/'.$timeFront[0].'('.$timeBack.','.$timesBack.')';
							}
							// echo $time.'<br/>';
							$count++;
						}else{
							if($i==0){
								$myTime .= $searchTime[$i];
							}
							else{
								$myTime .= '/'.$searchTime[$i];
							}
							// echo $time.'<br/>';
							// $time = $searchTime.'/'.$time;
							// var_dump($time);
						}
						// var_dump($myTime);
					}
					if($count==0){
						$myTime = $rs['time'].'/'.$time;	
					}
					$rs['time'] = $myTime;
					$teacherSchedulesModel->save($rs);
				}
				else{
					$data['tea_no'] = $tea_no;
					$data['subject_name'] = $subject_name;
					$data['time'] = $time;
					$data['week_day'] = $week_day;
					$data['room_name'] = $room_name;
					$data['classes_name'] = $classes_name;
					$data['term_id'] = $term_id;
					M('teacherSchedules')->add($data);
				}
			}
		}
	}
	/*导入教师课表*/
	
	
	/*导入教室容量*/
	public function exportRoomBigSmall(){
		if (!empty($_FILES)) {
            $filename = 'temp.xls';
			$tmp_name = $_FILES['file']['tmp_name'];
			move_uploaded_file($tmp_name,'Public/temp/'.$filename);
			$this->export5('temp/'.$filename);
		}
		$this->success('导入成功','init');
	}
	public function export5($load){
		require_once 'module/PHPExcel/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = \PHPExcel_IOFactory::load("Public/".$load);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet0=$objPHPExcel->getSheet(0);
		$rowCount=$sheet0->getHighestRow();				//获取excel行数
		// $room_name = $objPHPExcel->getActiveSheet()->getCell("B251")->getValue();
		// $can_use = (int)$objPHPExcel->getActiveSheet()->getCell("I251")->getValue();
		// $room_volume = (int)$objPHPExcel->getActiveSheet()->getCell("J251")->getValue();
		for ($i = 2; $i <= $rowCount; $i++){	//循环导入信息	
			$room_name = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
			$can_use = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
			$room_volume = $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
			$condition['room_name'] = $room_name;
			$roomModel = M('room');
			$data['can_use'] = $can_use;
			$data['room_volume'] = $room_volume;
			$result = $roomModel->where($condition)->save($data);
			
		}
	}
	/*导入教室容量*/
	
	/*导入学生信息*/
	public function exportStuMes(){
		if (!empty($_FILES)) {
            $filename = 'd.xls';
			$tmp_name = $_FILES['file']['tmp_name'];
			move_uploaded_file($tmp_name,'Public/temp/'.$filename);
			// echo $filename;
			$this->export4('temp/'.$filename);
			// $this->success('导入成功','Mains');
		}
		// $this->export2('班级名称：15ERP1(59人)    班级代码：01051501.xls');
		$this->success('导入成功','init');
	}
	//多个导入
	public function exprotManyStuMes(){
		$filename = 'd.zip';
		$tmp_name = $_FILES['file2']['tmp_name'];
		move_uploaded_file($tmp_name,'Public/temp/'.$filename);
		$zip = new MyUnit();			//实例化zip工具类
		$zipfile   = BASE_PATH.'Public/temp/'.$filename;				//压缩文件名
		$savepath  = BASE_PATH.'Public/upload4/';						//解压缩目录名
		$array = $zip->GetZipInnerFilesInfo($zipfile);
		// var_dump($array);
		$filecount = 0;
        $dircount  = 0;
        $failfiles = array();
		for($i=0; $i<count($array); $i++) {
            if($array[$i]['folder'] == 0){
                if($zip->unZip($zipfile, $savepath, $i) > 0){
                    $filecount++;
                }else{
                    $failfiles[] = $array[$i]['filename'];
                }
            }else{
                $dircount++;
            }
        }
		// Open a known directory, and proceed to read its contents
		$dir = "./Public/upload4/";
		$file=scandir($dir);
		for($i=2;$i<count($file);$i++){
			$this->export4('upload4/'.$file[$i]);									//遍历文件，实现多导入
		}
		$this->success('导入成功','init');
	}
	public function export4($load){
		require_once 'module/PHPExcel/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = \PHPExcel_IOFactory::load("Public/".$load);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet0=$objPHPExcel->getSheet(0);
		$rowCount=$sheet0->getHighestRow();//  			获取excel行数
		$columnCount=$sheet0->getHighestColumn();		//获取excel列数
		$columnCount= \PHPExcel_Cell::columnIndexFromString($columnCount); //字母列转换为数字列如
		//var_dump($rowCount);
		// 开始处理学生信息
		for ($i = 2; $i <= $rowCount; $i++){	//循环导入信息	
			$stuNo = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
			$classes_name = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
			//找出班级ID
			$classes_names = explode("（",$classes_name);
			if(count($classes_names)==2){
				$classes_name = $classes_names[0];
			}
			$condition['classes_name'] = $classes_name;
			$rs = M('classes')->where($condition)->find();
			if($rs!=null){
				$classes_id = $rs['classes_id'];
			}else{
				$classes_id = "";
			}
			$stu_name = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();	//获得姓名
			$stu_sex = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();	
			$stu_major = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
			//构造数据
			$data['stu_no'] = $stuNo;
			$data['stu_name'] = $stu_name;
			$data['stu_sex'] = $stu_sex;
			$data['stu_major'] = $stu_major;
			$data['stu_photo'] = '00.png';
			$data['stu_password'] = '123';
			$data['classes_id'] = $classes_id;
			// var_dump($data);
			M('student')->add($data);
		}
	}
	
	
	
	/*导入学生信息*/
	
	
	
	//删除某个文件夹下的文件
	public function deleteAllFile($dirName){
		$dir = 'Public/upload1';
		$dh=opendir($dir);
		while ($file=readdir($dh)) 
		{
			if($file!="." && $file!="..") 
			{
				$fullpath=$dir."/".$file;
				
				if(!is_dir($fullpath))
				{
					unlink($fullpath);
				} 
				else
				{
					deldir($fullpath);
				}
			}
		}
		closedir($dh);
	}
	
	
	//进入管理员主页面
	public function Mains(){
		$this->display('admin');
	}
	
	//管理员验证登陆
	public function login(){
		session_start();
		$a_account = I('post.a_account','');
		if($a_account != ''){
			$a_password = I('post.a_password','');
			$condition['a_account'] = $a_account;
			$condition['a_password'] = md5($a_password);
			$adminModel = M('admin');
			$rs = $adminModel->where($condition)->find();
			// var_dump($rs);
			if($rs!=null){
				$_SESSION['user'] = $rs;
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	
	//Excel表的导入
	// public function export($load){
		// require_once 'module/PHPExcel/Classes/PHPExcel/IOFactory.php';
		// $objPHPExcel = \PHPExcel_IOFactory::load("Public/upload/".$load);
		// $objPHPExcel->setActiveSheetIndex(0);
		// $sheet0=$objPHPExcel->getSheet(0);
		// $rowCount=$sheet0->getHighestRow();//  			获取excel行数
		// $data=array();
		// for ($i = 2; $i <= $rowCount; $i++){													//循环导入信息
			// $data['term'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
			// $data['room_name'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();  				
            // $data['week_record'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            // $data['week_day'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            // $data['time_interval'] = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            
			// $data['classes_name'] = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            // $data['subject_name']= $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            // $data['teacher_name']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            // $data['room_status']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue(); 
			// M('schoolTime')->add($data);
		// }
	
	// }
	/**/
	
	
	// 单个excel文件进行导入
	// public function exportExcel(){
		// if (!empty($_FILES)) {
            // $filename = $_FILES['file']['name'];
			// $tmp_name = $_FILES['file']['tmp_name'];
			// move_uploaded_file($tmp_name,'Public/upload/'.$filename);
			// $this->export($filename);
		// }
		// echo '导入成功';
	// }  
	
	//多个Excel文件进行导入
	// public function exprotExcelMany(){
		
		// Open a known directory, and proceed to read its contents
		// $dir = "./Public/upload/";
		// $file=scandir($dir);
		// for($i=2;$i<count($file);$i++){
			// $this->export($file[$i]);									//遍历文件，实现多导入
		// }
		// echo '导入成功';
	// }
	
	//查看自己的申请情况
	public function watchRoomApply(){
		$roomApplyModel = M('roomApply');
		// $count = $roomApplyModel->count();   // 查询满足要求的总记录数
		// $size = 5;							   //规定每页只显示的记录的条数
		// $page_num=ceil($count/$size);			//算出总页数
		// if(@$_GET['page_id']){
			// $page_id = $_GET['page_id'];
			// $start = ($page_id-1)*$size;
		// }else{
			// $page_id=1;
			// $start=0;
		// }
		$ret = array('ret'=>null,'status'=>'lose');
		$user_id = I('user_id','');
		if($user_id!=''){
			$condition['user_id'] = $user_id;
			$applys = $roomApplyModel->where($condition)->order('apply_date desc')->select();
			$ret = array('mes'=>$applys,'mes_num'=>count($applys));
			// $this->assign('page_id',$page_id);	
			// $this->assign('page_num',$page_num);
			// $this->assign('size',$size);
			// $this->assign('count',$count);
			// $this->assign('applys',$applys);	
			// $this->display('showApply');
			$ret = array('ret'=>$ret,'status'=>'success');
		}
		echo json_encode($ret);
	} 
	
	//进入管理前台管理员页面
	public function userManager(){
		$this->display('userAdmin');
	}
	
	
	//管理员注销
	public function managerExit(){
		session_start();
		if(isset($_SESSION['user'])){
			unset($_SESSION['user']);
		}
		$this->display('login');
	}
	
	//获取前台管理员用户列表
	public function getHomeAdmin(){
		$home_admin_account = isset($_POST['home_admin_account']) ? $_POST['home_admin_account'] : '';
		$home_admin_name = isset($_POST['home_admin_name']) ? $_POST['home_admin_name'] : '';
		$condition['home_admin_account'] = array('like',"%$home_admin_account%");
		$condition['home_admin_name'] = array('like',"%$home_admin_name%");
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;		//页数
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;	//每页显示的行数
		$count = M('homeAdmin')->where($condition)->count();								//算出数据总数
		//$page_num=ceil($count/$rows);								//算出总页数
		$start=($page-1)*$rows;
		$rs = M('homeAdmin')->where($condition)->limit($start,$rows)->select();
		$ret['total'] = $count;
		$ret['rows'] = $rs;
		$ret = json_encode($ret);
		echo $ret;
	}
	
	//保存前台管理员
	public function saveHomeUser(){
		$ret = -1;
		$home_admin_account = I('post.home_admin_account','');
		if($home_admin_account != ''){
			$home_admin_name = I('post.home_admin_name','');
			$home_admin_password = I('post.home_admin_password','');
			$data['home_admin_account'] = $home_admin_account;
			$data['home_admin_name'] = $home_admin_name;
			$data['home_admin_password'] = $home_admin_password;
			$ret = M('homeAdmin')->add($data);
		}
		echo $ret;
	}
	//编辑前台管理员
	public function editHomeUser(){
		$ret = -1;
		$home_admin_id = I('get.home_admin_id','');
		if($home_admin_id != ''){
			$home_admin_account = I('post.home_admin_account','');
			$home_admin_name = I('post.home_admin_name','');
			$home_admin_password = I('post.home_admin_password','');
			$homeAdminModel = M('homeAdmin');
			$homeAdminModel->find($home_admin_id);
			$homeAdminModel->home_admin_account = $home_admin_account;
			$homeAdminModel->home_admin_name = $home_admin_name;
			$homeAdminModel->home_admin_password = $home_admin_password;
			$homeAdminModel->save();
			$ret = 1;
		}
		echo $ret;
	}
	//删除前台管理员
	public function destroyHomeUser(){
		// echo 1;
		$home_admin_id = I('post.home_admin_id','');
		$a = M('homeAdmin')->delete($home_admin_id);
		echo $a;
	}
	
	public function myTest(){
		echo 'adsf登录成';
	}
	
	//用于跳转至学生权限管理页面
	public function showStudentSpe(){
		$this->display('studentSpecial');
	}
	
	//显示学生权限
	public function getStudentSpe(){
		$stu_no = isset($_POST['stu_no']) ? $_POST['stu_no'] : '';		//接受要查询的条件
		$stu_name = isset($_POST['stu_name']) ? $_POST['stu_name'] : '';
		if($stu_no != ''){
			$condition['cm_authority.stu_no'] = array('like',"%$stu_no%");
		}
		if($stu_name != ''){
			$condition['stu_name'] = array('like',"%$stu_name%");
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;		//页数
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;	//每页显示的行数
		$count = M('authority')->where($condition)->count();
		$start=($page-1)*$rows;
		$rs = M('authority')->where($condition)->field('cm_student.stu_no,stu_name,authority')->join('cm_student ON cm_authority.stu_no = cm_student.stu_no')->limit($start,$rows)->select();
		// 重新封装下数据
		foreach($rs as $key=>$val){
			$rs[$key]['authority'] = '是';
		}
		$result['total'] = $count;
		$result['rows'] = $rs;
		// var_dump($rs);
		echo json_encode($result);
	}
	
	//将学生加入特殊权限
	public function addAuthority(){
		$ret = -1;
		$stu_no = I('post.stu_no','');
		$condition['stu_no'] = $stu_no;
		$rs = M('student')->where($condition)->find();
		if($rs!=null){
			$data['stu_no'] = $stu_no;
			$data['authority'] = 1;
			$ret = M('authority')->add($data);
		}
		echo $ret;
	}
	
	//将学生移出此权限
	public function removeAuthority(){
		$stu_no = I('post.stu_no','');
		$condition['stu_no'] = $stu_no;
		$a = M('authority')->where($condition)->delete();
		echo $a;
	}
	
	//导入数据
	public function init(){
		$this->display('systemInit');
	}
	
	//清空所有数据
	public function MesTrun()
	{
		M()->execute('call clearTable()');
		$this->success('初始化成功','Mains');
	}
	
	
	
	//上传并解压测试
	public function mymy(){
		$filename = 'a.zip';
		$tmp_name = $_FILES['file2']['tmp_name'];
		move_uploaded_file($tmp_name,'Public/temp/'.$filename);
		$zip = new MyUnit();			//实例化zip工具类
		$zipfile   = BASE_PATH.'Public/temp/'.$filename;				//压缩文件名
		$savepath  = BASE_PATH.'Public/upload1/';						//解压缩目录名
		$array = $zip->GetZipInnerFilesInfo($zipfile);
		// var_dump($array);
		$filecount = 0;
        $dircount  = 0;
        $failfiles = array();
		for($i=0; $i<count($array); $i++) {
            if($array[$i]['folder'] == 0){
                if($zip->unZip($zipfile, $savepath, $i) > 0){
                    $filecount++;
                }else{
                    $failfiles[] = $array[$i]['filename'];
                }
            }else{
                $dircount++;
            }
        }
		// $this->export1('temp/'.$filename);
		// $zip = new \ZipArchive();
		// $file_root = BASE_PATH.'Public/temp/'.$filename;
		// $res = $zip->open($file_root);
		// var_dump($zip);
		// if ($res === TRUE){ 
			// echo 'ok'; 
			// 解压缩到test文件夹 
			
			// $contents = $zip->getFromIndex(1);
			// var_dump($contents);
			// $zip->extractTo(BASE_PATH.'Public/upload1'); 
			// $zip->close(); 
		// }else { 
			// echo 'failed, code:' . $res; 
		// } 
	}
	
	
	
	public function test10(){
		
	}
	
	
	
	
}
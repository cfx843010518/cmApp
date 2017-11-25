<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>测试</title>
</head>
<body>
<center>
	<h1>查询结果</h1>
	
	<?php if(count($res)!=0){ ?>
	<table style="text-align:center" border="1">
	<tr>
		<th>教室名称</th>
		<th>教室容纳人数</th>
		<th>使用周次</th>
		<th>使用时间段</th>
		<th>星期</th>
	</tr>
	<?php foreach($res as $key=>$val){?>
	<tr>
		<td><?php echo $val['room_name']?></td>
		<td><?php echo $val['room_volume']?></td>
		<td><?php echo $val['week_record']?></td>
		<td><?php echo $val['time_interval']?></td>
		<td><?php echo $val['week_day']?></td>
	
	</tr>
	<?php }?>
	</table>
	<?php }else{?>
		无查询结果,是否进行预约？
		<form action="" method="post">
			学期id:<input type="text" name="term_id"/><br/><br/>
			周次：<input type="text" name="week_record"/><br/><br/>
			班级id:<input type="text" name="classes_id"/><br/><br/>
			课程编号：<input type="text" name="subject_id"/><br/><br/>
			节数：<input type="text" name="pitch_number"/><br/><br/>
			时间段：<input type="text" name="time_quantum"/><br/><br/>
			教师id:<input type="text" name="tea_id"/><br/><br/>
			教室id:<input type="text" name="classroom_id"/><br/><br/>
			星期：<input type="text" name="week_day"/><br/><br/>
			<input type="submit" value="预约"/>
		</form>
	<?php }?>
	
		
	
</center>
</body>
</html>
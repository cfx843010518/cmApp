<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>测试</title>

</head>
<body>
<!--
	<form action="photo.php" method="post" enctype="multipart/form-data">
		<input type="text" name="user_account"/>
		<input type="password" name="user_password"/>
		<input type="file" name="myFile"/>
		<input type="submit" value="登录"/>
	</form>
	<a href="http://localhost/zfApp/product/test.php">测试</a>
-->
<!--
	<form action="getMyRecordSubject" method="GET">
		学生id：<input type="text" name="stu_id"/>
		<input type="submit" value="登录"/>
	</form>
	-->
		
	<form action="showConditionRoom" method="POST">
		room_type_id：<input type="text" name="mes"/><br/>
		<input type="submit" value="登录"/><br/>
	</form>
	
	
	<!--
	我要调课<br/>
	<form action="changeClassSchedule" method="GET">
		tea_sch_id：<input type="text" name="tea_sch_id"/><br/>
		week_recordB:<input type="text" name="week_recordB"/><br/>
		room_name：<input type="text" name="room_name"/><br/>
		week_recordA:<input type="text" name="week_recordA"/><br/>
		time_interval:<input type="text" name="time_interval"/><br/>
		week_day: <input type="text" name="week_day"/><br/>
		<input type="submit" value="登录"/><br/>
	</form>
	-->
	<!--
	<form action="watchMyApply" method="GET">
		
		tea_no: <input type="text" name="tea_no"/><br/>
		<input type="submit" value="登录"/><br/>
	</form>
	watchMyApply
	
</body>
</html>
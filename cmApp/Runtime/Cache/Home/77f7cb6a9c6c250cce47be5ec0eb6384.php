<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>测试</title>
</head>
<body>
	<form action="searchRoomFree" method="GET">
		<select name="room_type_id">
			<option value="1">多媒体教室</option>
			<option value="2">普通教室</option>
		</select><br/><br/>
		星期：<input type="text" name="week_day"/> <br/><br/>
		时间段：<input type="text" name="time_interval"/> <br/><br/>
		周次：<input type="text" name="week_record"/> <br/><br/>
		<input type="submit" value="查询"/>
	</form>
</body>
</html>
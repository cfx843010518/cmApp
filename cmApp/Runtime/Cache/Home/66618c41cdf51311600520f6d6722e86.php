<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>测试</title>
</head>
<body>
<center>
	<form action="applyRoom" method="post">
		用户id:<input type="text" name="user_id"/><br/>
		活动类型:<input type="text" name="active_type"/><br/>
		主题:<input type="text" name="active_theme"/><br/>
		时间段:<input type="text" name="time_interval"/><br/>
		周次:<input type="text" name="week_record"/><br/>
		星期:<input type="text" name=" week_day"/><br/>
		主办方:<input type="text" name="sponsor"/><br/>
		课室编号:<input type="text" name="room_name"/><br/>
		申请理由:<input type="text" name="apply_reason"/><br/>
		是否分享:<input type="text" name="is_share"/><br/>
		<input type="submit" value="登录"/>
	</form>
</body>
</html>
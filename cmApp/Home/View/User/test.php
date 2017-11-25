<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	<form action="checkUser" method="post">
		用户名(学号/教职工编号)<input type="text" name="user_account"/>
		密码：<input type="password" name="user_password"/>
		角色：<input type="text" name="user_role"/>
		<input type="submit" value="登录"/>
	</form>
-->
	<!--
	<form action="updatePassword" method="post">
		user_id:(学号/教职工编号)<input type="text" name="user_id"/>
		密码：<input type="password" name="user_password"/>
		角色：<input type="text" name="user_role"/>
		<input type="submit" value="登录"/>
	</form>
	-->
	
		<form action="updatePhoto" method="POST" enctype="multipart/form-data">
		user_id:(学号/教职工编号)<input type="text" name="user_id"/>
		头像：<input type="file" name="user_photo"/>
		角色：<input type="text" name="user_role"/>
		<input type="submit" value="登录"/>
		</form>
	
		<!--
		<form action="http://39.108.96.188:8088/cmApp/User/myTest" method="POST" enctype="multipart/form-data">
		
		角色：<input type="text" name="abc"/>
		<input type="submit" value="登录"/>
		</form>
		-->
</body>
</html>
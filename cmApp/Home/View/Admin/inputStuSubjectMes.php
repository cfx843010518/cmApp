<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Basic Form - jQuery EasyUI Demo</title>
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/demo.css">
	<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/jquery.easyui.min.js"></script>
</head>
<body>
<?php
session_start();
if(!isset($_SESSION['user'])){
	header("Location: index");
}
?>
<center><h1>课室管理后台系统</h1></center>
<p style="margin-left:100px;">欢迎你，管理员：<?php echo $_SESSION['user']['a_account'];?>&nbsp;&nbsp;<a href="updatePwd">修改密码</a>&nbsp;&nbsp;<a href="managerExit">注销</a><p>
<div class="easyui-layout" style="width:1100px;height:400px;margin-left:100px;">
	<div region="west" split="true" title="导航栏" style="width:200px;">
		<p style="padding:5px;margin:0;"><strong>基础信息录入</strong></p>
		<ul>
			<li><a href="inputRoomMes">教室安排表录入</a></li>
			<li><a href="inputStuSubjectMes">学生课程表录入</a></li>
			<li><a href="inputTeaSubjectMes">教师课表录入</a></li>
		</ul>
		<p style="padding:5px;margin:0;"><strong>用户管理(管理权限)</strong></p>
		<ul>
			<li><a href="userManager">前台管理员</a></li>
			<li><a href="showStudentSpe">学生特权用户</a></li>
		</ul>
	</div>
	<div id="content" region="center" title="内容" style="padding:5px;">
		<center>
		<h1>学生课表导入</h1>
		<strong>1.单个Excel表导入(已导入过的表切记不可重复导入):</strong><br/><br/>
		<form action="exportStuSubMes" method="post" enctype="multipart/form-data">
			<input type="file" name="file"/>
			<input type="submit" value="导入"/>
		</form>
		<br/><br/>
		<strong>2.多个Excel表的导入(请将Excel文件放在项目Public/upload2目录下,导入时间较长，需耐心等待)</strong>
		<a href="exprotManyStuSub" onclick="return confirm('确定要导入数据，该操作只有在系统初始化的时候才能使用，重复导入数据会导致系统出错！')">多个文件导入</a>
		<br/><br/>
		</center>
	</div>
</div>
</body>
</html>
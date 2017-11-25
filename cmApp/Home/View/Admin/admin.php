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
		<p style="padding:5px;margin:0;"><strong>基础信息录入|删除</strong></p>
		<ul>
			<li><a href="init">初始化信息录入</a></li>
			<li><a href="MesTrun" onclick="return confirm('确定要清空所有数据？(该操作不可逆)')">清空数据</a></li>
		</ul>
		<p style="padding:5px;margin:0;"><strong>用户管理(管理权限)</strong></p>
		<ul>
			<li><a href="userManager">前台管理员</a></li>
			<li><a href="showStudentSpe">学生特权用户</a></li>
		</ul>
	</div>
	<div id="content" region="center" title="内容" style="padding:5px;">
		
	</div>
</div>
</body>
</html>
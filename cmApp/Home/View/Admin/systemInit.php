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
<style type="text/css">
	a:link {
		text-decoration: none;
		color:black;
	}
</style>
<script>
	var temp;
	//录入信息页面(单个表)
	function newInitOne(evt){
		temp = evt;
		if(temp==1){
			$('#dlg').dialog('open').dialog('setTitle','单个教室课表信息录入');
			$('#fm').form('clear');
		}
		else if(temp==2){
			$('#dlg').dialog('open').dialog('setTitle','单个学生课表信息录入');
			$('#fm').form('clear');
		}
		else if(temp==3){
			$('#dlg').dialog('open').dialog('setTitle','单个教师课表信息录入');
			$('#fm').form('clear');
		}else if(temp==4){
			$('#dlg').dialog('open').dialog('setTitle','教室容量信息录入');
			$('#fm').form('clear');
		}else{
			$('#dlg').dialog('open').dialog('setTitle','学生信息录入');
			$('#fm').form('clear');
		}
	}
	
	//录入信息页面(多个表)
	function newInitMany(evt){
		temp = evt;
		if(temp==1){
			$('#dlg2').dialog('open').dialog('setTitle','多个教室课表信息录入');
			$('#fm2').form('clear');
		}
		else if(temp==2){
			$('#dlg2').dialog('open').dialog('setTitle','多个学生课表信息录入');
			$('#fm2').form('clear');
		}
		else if(temp==3)
		{
			$('#dlg2').dialog('open').dialog('setTitle','多个教师课表信息录入');
			$('#fm2').form('clear');
		}else if(temp==5){
			$('#dlg2').dialog('open').dialog('setTitle','多个学生信息录入');
			$('#fm2').form('clear');
		}
	}
	
	//录入信息(单个)
	function saveRoomMes(){
		if(temp==1){
			var isNull = $('#file').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				$('#fm').attr("action","exportRoomMes"); 
				$('#fm').submit();
			}
		}
		else if(temp==2){
			var isNull = $('#file').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				$('#fm').attr("action","exportStuSubMes"); 
				$('#fm').submit();
			}
		}
		else if(temp==3){
			var isNull = $('#file').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				$('#fm').attr("action","exportTeaMes"); 
				$('#fm').submit();
			}
		}
		else if(temp==4){
			var isNull = $('#file').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				$('#fm').attr("action","exportRoomBigSmall"); 
				$('#fm').submit();
			}
		}
		else{
			var isNull = $('#file').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				$('#fm').attr("action","exportStuMes"); 
				$('#fm').submit();
			}
		}
	}
	
		//录入信息(多个)
	function saveRoomsMes(){
		// alert('asdfadf');
		// alert(temp);
		if(temp==1){
			var isNull = $('#file2').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				// alert('a');
				$('#fm2').attr("action","exprotExcelMany");
				$('#fm2').submit();
			}
		}
		else if(temp==2)
		{
			var isNull = $('#file2').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				// alert('a');
				$('#fm2').attr("action","exprotManyStuSub");
				$('#fm2').submit();
			}
		}
		else if(temp==3){
			var isNull = $('#file2').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				// alert('a');
				$('#fm2').attr("action","exprotManyTeaSub");
				$('#fm2').submit();
			}
		}
		else if(temp==5){
			var isNull = $('#file2').val();
			if(isNull==""){
				alert('请选择文件');
			}else{
				// alert('a');
				$('#fm2').attr("action","exprotManyStuMes");
				$('#fm2').submit();
			}
		}
	}
</script>
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
		<!--创建菜单按钮-->
		<div style="background:#fafafa;padding:5px;width:650px;border:1px solid #ccc">
			<a href="#" class="easyui-menubutton" menu="#mm1" iconCls="icon-edit">教室课表导入</a>
			<a href="#" class="easyui-menubutton" menu="#mm2" iconCls="icon-edit">学生课表导入</a>
			<a href="#" class="easyui-menubutton" menu="#mm3" iconCls="icon-edit">教师课表导入</a>
			<a href="#" class="easyui-menubutton" menu="#mm4" iconCls="icon-edit">教室容量导入</a>
			<a href="#" class="easyui-menubutton" menu="#mm5" iconCls="icon-edit">学生信息导入</a>
		</div>
		<div id="mm1" style="width:150px;">
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitOne(1)">单个教室课表导入</a></div>
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitMany(1)">多个教室课表导入</a></div>
		</div>
		<div id="mm2" style="width:150px;">
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitOne(2)">单个学生课表导入</div>
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitMany(2)">多个学生课表导入</div>
		</div>
		<div id="mm3" style="width:150px;">
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitOne(3)">单个教师课表导入</div>
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitMany(3)">多个教师课表导入</div>
		</div>
		<div id="mm4" style="width:150px;">
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitOne(4)">导入教室容量</div>
		</div>
		<div id="mm5" style="width:150px;">
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitOne(5)">单个学生信息表导入</div>
			<div iconCls="icon-redo"><a href="javascript:void(0)" onclick="newInitMany(5)">多个学生信息表导入</div>
		</div>
		<!--新增单个表-->
		<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
			<form id="fm" method="post" enctype="multipart/form-data">
					<div class="fitem">
						<label>选择表:</label>
						<input name="file" type="file" id="file" required="true">
					</div><br/>
			</form>
			<div class="ftitle" style="color:red;">注意事项：已导入过的表切记不可重复导入</div><br/>
		</div>
		<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveRoomMes()">导入</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
		</div>
		<!--新增多个表-->
		<div id="dlg2" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
			<form id="fm2" method="post" enctype="multipart/form-data">
					<div class="fitem">
						<label>选择文件:</label>
						<input name="file2" type="file" id="file2" required="true">
					</div><br/>
			</form>
			<div class="ftitle" style="color:red;">注意事项：必须将多个Excel文件打包成一份为zip后缀的压缩文件</div><br/>
		</div>
		<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveRoomsMes()">导入</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg2').dialog('close')">取消</a>
		</div>
	</div>
</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>学生权限管理</title>
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/demo.css">
	<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/jquery.easyui.min.js"></script>
</head>
<script type="text/javascript">
	var url;
	//新增用户界面
	function newUser(){
		$('#dlg').dialog('open').dialog('setTitle','新增学生权限');
		$('#fm').form('clear');
		url = 'addAuthority';
	}
	
	
	//新增和编辑用户操作
	function saveUser(){
		$('#fm').form('submit',{
			url:url,
			onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			if (result==-1){
				$.messager.show({
					title: 'Error',
					msg: '学号不存在！'
				});
			} else {
				// alert('新增成功');
				$('#dlg').dialog('close');		// 关闭数据
				$('#dg').datagrid('reload');	// 刷新用户数据 
			}
		}
	});
	}
	
	//删除一个用户
	function destroyUser(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','确定要移除该学生此权限？',function(r){
			if (r){
				$.post('removeAuthority',{stu_no:row.stu_no},function(result){
					// alert(result);
					if (result==1){
						$('#dg').datagrid('reload');	// 重新加载数据
						
					} else {
						$.messager.show({	// show error message
							title: 'Error',
							msg: result.errorMsg
						});
					}
				},'json');
			}
		});
	}
	}
	//搜索权限学生
	function doSearch(){
		$('#dg').datagrid('load',{
			stu_no: $('#stu_no').val(),
			stu_name: $('#stu_name').val()
		});
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
<div class="easyui-layout" style="width:1100px;height:450px;margin-left:100px;">
	<div region="west" split="true" title="导航栏" style="width:200px;">
		<p style="padding:5px;margin:0;"><strong>基础信息录入</strong></p>
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
	<!--右边主要内容模块-->
	<div id="content" region="center" title="学生权限列表" style="padding:5px;">
		<!--搜索框-->
		<div id="tb" style="padding:3px">
			<span>学号:</span>
			<input id="stu_no" style="line-height:26px;border:1px solid #ccc">
			<span>姓名:</span>
			<input id="stu_name" style="line-height:26px;border:1px solid #ccc">
			<a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">搜索</a>
		</div>
		<!--数据表-->
		<table id="dg" class="easyui-datagrid" style="width:850px;height:365px" 
				url="getStudentSpe"
				toolbar="#toolbar"
				rownumbers="true" fitColumns="true" singleSelect="true" pagination="true">
			<thead>
				<tr>
					<th field="stu_no" width="50">学号</th>
					<th field="stu_name" width="50">姓名</th>
					<th field="authority" width="50">是否拥有权限</th>
				</tr>
			</thead>
		</table>
		<!--编辑列表-->
		<div id="toolbar">
			<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">将学生加入此权限</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">将学生从此权限移除</a>
		</div>
		<!--新增用户界面-->
		<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons">
		<div class="ftitle">学生信息</div><br/>
			<form id="fm" method="post">
				<div class="fitem">
					<label>学号:</label>
					<input name="stu_no" class="easyui-validatebox" required="true">
				</div><br/>
			</form>
		</div>
		<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">保存</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
		</div>
	</div>
</div>

</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Basic Form - jQuery EasyUI Demo</title>
	<link rel="stylesheet" type="text/css" href="/cmApp/Public/js/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="/cmApp/Public/js/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="/cmApp/Public/css/demo.css">
	<script type="text/javascript" src="/cmApp/Public/js/jquery.min.js"></script>
	<script type="text/javascript" src="/cmApp/Public/js/jquery.easyui.min.js"></script>
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
				alert('新增成功');
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
</script>
<body>
<?php
session_start(); if(!isset($_SESSION['user'])){ header("Location: index"); } ?>
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
	<!--右边主要内容模块-->
	<div id="content" region="center" title="内容" style="padding:5px;">
		<div style="margin:20px 0;"></div>
		<div class="easyui-panel" title="管理员修改密码" style="width:400px">
		<div style="padding:10px 60px 20px 60px">
	    <form id="ff" method="post">
	    	<table cellpadding="5">
	    		<tr>
	    			<td>用户名:</td>
	    			<td><input class="easyui-textbox" type="text" name="a_account" value="<?php echo $_SESSION['user']['a_account'];?>" data-options="required:true"></input></td>
	    		</tr>
	    		<tr>
	    			<td>密码:</td>
	    			<td><input class="easyui-textbox" type="password" name="a_password" data-options="required:true"></input></td>
	    		</tr>
	    	</table>
	    </form>
	    <div style="text-align:center;padding:5px">
	    	<a  class="easyui-linkbutton" onclick="submitForm()">修改</a>
	    	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()">重置</a>
	    </div>
	    </div>
	</div>
	<script>
		function submitForm(){
			 $('#ff').form('submit',{   
     		 url:'updatePwdAction',   
     		    
			success:function(data){   
				if(data==1){
					alert('修改成功');
					location.href="Mains";
				}else{
					alert('修改失败！');
					location.href="Mains";
				}
			}	   
		});  
		}
		function clearForm(){
			$('#ff').form('clear');
		}
	</script>
	</div>
</div>

</body>
</html>
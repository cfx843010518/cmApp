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
<body><center>
	<h2>课室后台管理系统</h2>
	<div style="margin:20px 0;"></div>
	<div class="easyui-panel" title="用户登录" style="width:400px">
		<div style="padding:10px 60px 20px 60px">
	    <form id="ff" method="post">
	    	<table cellpadding="5">
	    		<tr>
	    			<td>用户名:</td>
	    			<td><input class="easyui-textbox" type="text" name="a_account" data-options="required:true"></input></td>
	    		</tr>
	    		<tr>
	    			<td>密码:</td>
	    			<td><input class="easyui-textbox" type="password" name="a_password" data-options="required:true"></input></td>
	    		</tr>
	    	</table>
	    </form>
	    <div style="text-align:center;padding:5px">
	    	<a  class="easyui-linkbutton" onclick="submitForm()">登录</a>
	    	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()">重置</a>
	    </div>
	    </div>
	</div>
	<script>
		function submitForm(){
			 $('#ff').form('submit',{   
     		 url:'login',   
     		    
			success:function(data){   
				if(data==1){
					// alert('登录成功');
					location.href="Mains";
				}else{
					alert('用户名或密码错误！');
					location.href="index";
				}
			}	   
		});  
		}
		function clearForm(){
			$('#ff').form('clear');
		}
	</script>
	<center>
</body>
</html>
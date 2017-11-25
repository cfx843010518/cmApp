<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<link href="__PUBLIC__/css/login.css" type="text/css" rel="stylesheet">
</head>
<body>
 <body>
   <div class="login">
    <div class="message">课室管理系统--管理员登录</div>
    
    <div id="darkbannerwrap"></div>
 
    <form method="post" action="login" name="form1" onsubmit="return check(this)">
		<input name="action" value="login" type="hidden">
		<input name="a_account" placeholder="用户名" required type="text">
		<hr class="hr15">
        <input name="a_password" placeholder="密码" required type="password">
		<hr class="hr15">
        <hr class="hr15">
		<input value="登录" style="width:100%;" type="submit">
		<hr class="hr20">
        
      <a href="javascript:void(0);" class="more1" onClick="alert('该通道暂时没打开')">注册</a>
        
  </form>
    	
</div>

<div class="copyright">课室管理系统 by NO.13</div>

</body>


</html>
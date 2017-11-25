<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>课室申请管理</title>
</head>
<script type="text/javascript">
	function allow(str){
		location.href = "allowRoom?room_apply_id="+str;
	}
</script>
<body>
<center>
	<h1>课室申请管理</h1>
	<table border="1" width="900px" style="text-align:center;">
		<tr>
			<th>申请者ID</th>
			<th>活动类型</th>
			<th>活动主题</th>
			<th>时间段</th>
			<th>周次</th>
			<th>星期</th>
			<th>主办方</th>
			<th>教室</th>
			<th>申请时间</th>
			<th>是否同意申请</th>
		</tr>
		<?php foreach($applys as $key=>$val){?>
		<tr>
			<td><?php echo $val['user_id'];?></td>
			<td><?php echo $val['active_type'];?></td>
			<td><?php echo $val['active_theme'];?></td>
			<td><?php echo $val['time_interval'];?></td>
			<td><?php echo $val['week_record'];?></td>
			<td><?php echo $val['week_day'];?></td>
			<td><?php echo $val['sponsor'];?></td>
			<td><?php echo $val['room_name'];?></td>
			<td><?php echo $val['apply_date'];?></td>
			<td>
				<?php if($val['is_approve']==0){?>
				<input type="button" value="同意" onclick="allow(<?php echo $val['room_apply_id']?>)"/>
				<?php }else{?>
				<input type="button" value="已同意" disabled="disabled"/>
				<?php }?>
			</td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="10">
			<?php
			echo "本站共有".$count."条记录&nbsp;";
			echo "每页显示".$size."条&nbsp;";
			echo "第".$page_id."页/共".$page_num."页&nbsp;";
			if($page_id>=1 && $page_num>1){
			echo "<a href=userManager?page_id=1>第一页&nbsp;</a>";
			}
			if($page_id>1 && $page_num>1){
			echo "<a href=userManager?page_id=".($page_id-1).">上一页&nbsp;</a>";
			}
			if($page_id>=1 && $page_num>$page_id){
			echo "<a href=userManager?page_id=".($page_id+1).">下一页&nbsp;</a>";
			}
			if($page_id>=1 && $page_num>1){
			echo "<a href=userManager?page_id=".$page_num.">尾页</a>";
			}
			?>
			</td>
		</tr> 
	<table>
</center>
</body>
</html>
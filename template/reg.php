<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p('注册账号 - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('reg');?>
<?php css('popup');?>
<?php js('jquery.min')?>
<?php js('md5')?>
</head>
<body>
<?php include("header.php");?>
<div class="center">
	<div class="reg f14">
		<div class="inner">
			<div class="tit"></div>
			<div class="log f12">如果您已经有账号，<a href="<?php p(webhost)?>">请直接登录 >></a></div>
			<div class="fm">
				<ul class="itm f12">
					<li>用户邮箱</li>
					<li>用户昵称</li>
					<li>邀请码</li>
					<li>设置密码</li>
					<li>确认密码</li>
				</ul>
				<ul class="input f12">
				<form method="post">
					<li><span class="box"><input type="text" name="email" wd="email"><small id="i1"></small></span><span class="comm">请输入常用邮箱，通过验证后可用于登录和找回密码</span></li>
					<li><span class="box"><input type="text" name="name" wd="name"><small id="i2"></small></span><span class="comm">不超过7个汉字，或14个字符（半角数字、半角字母或下划线），且不能是纯数字</span></li>
					<li><span class="box"><input type="text" name="code" wd="code" value="<?php p($code)?>"><small id="i3"></small></span><span class="comm">给好友提出缺点，就会有获得邀请码的可能性。</span></li>
					<li><span class="box"><input type="password" name="pwd" id="pwd1" wd="pwd"><small id="i4"></small></span><span class="comm">密码长度6~14位，字母区分大小写</span></li>
					<li><span class="box"><input type="password" name="pwd2" id="pwd2" wd="pwd2"><small id="i5"></small></span><span class="comm">请完整重复上面的密码，注意区分大小写<span></li>
					<li><span class="box"><input type="submit" name="reg" class="btn" wd='md5' value="提交信息并注册" style="width:140px;"></span></li>
				</form>
				<input type="hidden" value="1" id="f1">
				<input type="hidden" value="1" id="f2">
				<input type="hidden" value="1" id="f3">
				<input type="hidden" value="1" id="f4">
				<input type="hidden" value="1" id="f5">
				</ul>
			</div>
		</div>
	</div>
	<div class="ad">
	</div>
</div>
<?php include('footer.php');?>
<?php include("popup_login.php")?>
<?php js('jquery.masonry.min')?>
<?php js('md5');?>
<script type="text/javascript">
$(document).ready(function(){
	function getByteLen(val) { 
		var len = 0; 
		for (var i = 0; i < val.length; i++) { 
			if (val[i].match(/[^\x00-\xff]/ig) != null) 
			len += 2; 
			else 
			len += 1; 
			} 
		return len; 
	} 
		$("[wd='email']").blur(function(){
		var search_str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
		if(!search_str.test($("[wd='email']").val())){
			$("#i1").html('请正确填写邮箱');
			$("#f1").val(0);
		}
		else{
			$("#i1").html('');
			$("#f1").val(1);
		}
	});
	$("[wd='name']").keyup(function(){
		if(getByteLen($("[wd='name']").val())>14){
			$("#i2").html('你的昵称超长了');
			$("[wd='name']").val($("[wd='name']").val().substr(0,7));
			$("#f2").val(0);
		}
		else{
			$("#i2").html('');
			$("#f2").val(1);
		}
	});
	$("[wd='name']").blur(function(){
		if(getByteLen($("[wd='name']").val())>14){
			$("#i2").html('你的昵称超长了');
			$("#f2").val(0);
		}
		else{
			$("#i2").html('');
			$("#f2").val(1);
		}
	});
	$("[wd='code']").blur(function(){
		if(getByteLen($("[wd='code']").val())!=32){
			$("#i3").html('你的邀请码不正确');
			$("#f3").val(0);
		}
		else{
			$("#i3").html('');
			$("#f3").val(1);
		}
	});
	$("[wd='pwd']").blur(function(){
		if(getByteLen($("[wd='pwd']").val())>14||getByteLen($("[wd='pwd']").val())<6){
			$("#i4").html('你设置的密码长度不正确');
			$("#f4").val(0);
		}
		else{
			$("#i4").html('');
			$("#f4").val(1);
		}
	});
	$("[wd='pwd2']").blur(function(){
		if($("[wd='pwd']").val()!=$("[wd='pwd2']").val()){
			$("#i5").html('两次密码输入的不一致，请重新输入');
			$("#f5").val(0);
		}
		else{
			$("#i5").html('');
			$("#f5").val(1);
		}
	});
	$("form").submit(function(e){
	if(($("#f1").val()*$("#f2").val()*$("#f3").val()*$("#f4").val()*$("#f5").val())==0){
		e.preventDefault();
		alert("请完整填写全部信息");
	}
	else{
		$("#pwd1").val(hex_md5($("#pwd1").val()));
		$("#pwd2").val(hex_md5($("#pwd2").val()));
	}
	});
});
</script>
</body>
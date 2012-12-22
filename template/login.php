<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p($webinfo["webname"].' - '.$webinfo["websubname"]);?></title>
<meta name=description content="WhyDislike，每天都有更好的自己——那些年我们在追逐自己的梦想，有时忘记了回头看看。在身后，我们留给了人们些许不好的印象。你可以选择变得更强，在这里找寻更好的自己。">
<?php css('login',1341060707);?>
<?php js('md5');?>
<?php js('jquery.min');?>
</head>
<body>
<div class="vcenter">
	<div class="center">
		<div class="top">
			<div class="logo"></div>
			<div class="demo"></div>
		</div>
		<div class="bottom">
			<div class="form">
				<div class="form-inner">
					<div class="form-title"></div>
					<form method="post" action="center/">
					<div class="form-sub f12">
						<ul>
							<li class="focus" wd="norlog">普通登录</li>
							<li class="blur" wd="snslog">社交登录</li>
						</ul>
						<div class="renren-share">
						<iframe scrolling="no" frameborder="0" allowtransparency="true" src="http://widget.renren.com/plugin/followbutton?page_id=601310858&color=0&model=1" style="width:150px;height:40px;" ></iframe>
						</div>
						<div class="invisible-clear"></div>
					</div>
					
					<div class="form-input">
						<input type="text" class="<?php if(!(isset($error)&&$error=='pwd')){?>email<?php }else{?>blur<?php }?>" name="email" id="email" <?php if(isset($email)){p('value="'.$email.'"');}?>/>
						<input type="password" class="keyword" name="pwdv" id="pwdv"/>
						<input type="hidden" name="pwd" id="pwd">
						<?php if(isset($_GET['next'])){?>
						<input type="hidden" name="next" value="<?php p($_GET['next'])?>">
						<?php }?>
						<div class="invisible-clear"></div>
					</div>
					<div class="form-btn">
						<div class="save f12"><span class="checkbox"><input type="checkbox" name="save"/></span> 记住登录状态</div>
						<div><input type="submit" name="login" class="submit f14 b" value="登录" wd="md5"/></div>
					</div>
					<div class="invisible-clear"></div>
					<div class="form-reg f12">
					<a class="b" href="<?php p(webhost.'reg/')?>"><?php if(!(isset($error)&&$error=='pwd')){?>新用户<?php }?>注册</a><?php if(isset($error)&&$error=='pwd'){?><span>|</span><a href="#" wd="forget">忘记密码？</a><?php }?>
					</div>
					
					<div class="social" style="display:none">
						<div class="link">
						<ul>
						<?php include("include/plugin/social/index.php")?>
						</ul>
						<div class="invisible-clear"></div>
						</div>
					</div>
					</form>
				</div>
			</div>
			<div class="show">
				<ul>
				<?php foreach(getVerify() as $user){?>
				<li>
				<?php getHead($user['uid'])?>
				<a class="tit f12" href="<?php p(webhost.'u/'.$user['uid'].'/')?>" target="_blank">
					<div class="titin">
					<strong ><?php p($user['name'])?></strong><br/>
					<?php p(subString($user['description'],0,9))?>
					</div>
				</a>
				</li>
				<?php }?>
				</ul>
			</div>
			<?php include("footer.php");?>
		</div>
	</div>
</div>
<?php
if(isset($alert)){
	echo '<script type="text/javascript">alert("'.$alert.'");</script>';
}
?>

<script type="text/javascript">
$("[wd='md5']").click(function(){
	$("#pwd").val(hex_md5($("#pwdv").val()));
});
$("#email").focus(function(){
	$(this).removeClass().addClass("focus");
});
$("#pwdv").focus(function(){
	$(this).removeClass().addClass("focus");
});
$("#email").blur(function(){
	if($("#email").val()==''){
	$(this).removeClass().addClass("email");
	}
	else{
	$(this).removeClass().addClass("blur");
	}
});
$("#pwdv").blur(function(){
	if($("#pwdv").val()==''){
	$(this).removeClass().addClass("keyword");
	}
	else{
	$(this).removeClass().addClass("blur");
	}
});
$("[wd='norlog']").click(function(){
	$("[wd='norlog']").removeClass().addClass("focus");
	$("[wd='snslog']").removeClass().addClass("blur");
	$(".social").hide();
	$(".form-input").show();
	$(".form-btn").show();
	$(".form-reg").show();
});
$("[wd='snslog']").click(function(){
	$("[wd='norlog']").removeClass().addClass("blur");
	$("[wd='snslog']").removeClass().addClass("focus");
	$(".form-input").hide();
	$(".form-btn").hide();
	$(".form-reg").hide();
	$(".social").show();
});
<?php if(isset($error)&&$error=='pwd'){?>
$("[wd='forget']").click(function(){
	$("[wd='forget']").html('发送邮件中...');
	$.ajax({
	url: '<?php p($webinfo["webhost"].'include/ajax/forget.php');?>',
	type: 'POST',
	data:{ email: $("#email").val()},
	dataType: 'html',
	timeout: 17000,
	error: function(){
		$("[wd='forget']").html('找回密码');
		alert('请求发送超时，请重试');
	},
	success: function(html){
		$("[wd='forget']").html('');
		alert(html);
	}
	});
});
<?php }?>
</script>
</body>
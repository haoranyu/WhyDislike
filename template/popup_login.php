<style>
#popup,.popin{
height:199px;
}
.popsubt{
height:103px;
}
</style>
<div id="popup" class="fixie6" style="display:none">
<form method="post" style="margin:0;padding:0" action="<?php p($webinfo["webhost"].'center/');?>">
<div class="popin">
	<div class="poptit f14">
		<span>登录</span><span class="cross" wd="closeLogin">x</span>
	</div>
	<div class="popsubt f12">
		<div class="loginform">
		已有账号，在这里可以直接登录
		<input type="text" name="email" value="登录邮箱地址" title="登录邮箱地址" wd="hint" style="color:#999">
		<input type="text" id="password" style="color:#999" value="请输入你的登录密码" wd="loginFakePwd">
		<input type="password" name="pwdv" id="pwdv" style="display:none;color:#666"  wd="loginRealPwd">
		<input type="hidden" name="pwd" id="pwd">
		</div>
		<div class="reghint">
		还没有账号？<br /><a href="<?php p(webhost)?>reg/">申请加入</a>
		</div>
	</div>
	<div class="ipopbtn f12">
		<div class="ipopbtns">
		<input type="submit" name="login" value="登录" class="f12 popsubmit" wd="md5" style="float:left"/>
		<div class="rem f12"><input type="checkbox" name="save"><span>记住密码</span></div>
		<input type="hidden" name="cid" id="cid" value=""/>
		</div>
	</div>
</div>
</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("[wd='md5']").click(function(){
		$("#pwd").val(hex_md5($("#pwdv").val()));
	});
	$("[wd='closeLogin']").click(function(){
		$("#popup").hide(500);
	});
	$("[wd='openLogin']").click(function(){
		$("#popup").show(500);
	});
	$("[wd='loginFakePwd']").focus(function(){
		$("[wd='loginRealPwd']").css("display","inline");
		$(this).css("display","none");
		$("[wd='loginRealPwd']").focus()
	});
	$("[wd='loginRealPwd']").blur(function(){
		if($("[wd='loginRealPwd']").val()==''){
		$("[wd='loginFakePwd']").css("display","inline");
		$(this).css("display","none");
		}
	});
});
</script>
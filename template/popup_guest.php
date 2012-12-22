<style>
#popupC,.popin{
height:199px;
}
.popsubt{
height:103px;
}
</style>
<div id="popupC" class="fixie6" style="display:none">
<form method="post" style="margin:0;padding:0" action="<?php p($webinfo["webhost"].'center/');?>">
<div class="popin">
	<div class="poptit f14">
		<span>提交</span><span class="cross" wd="closeCaptcha">x</span>
	</div>
	<div class="popsubt f12">
		<div class="loginform">
		已有账号，请先登录再进行操作。
		<input type="text" name="email" value="登录邮箱地址" title="登录邮箱地址" wd="hint" style="color:#999">
		<input type="text" id="password" style="color:#999" value="请输入你的登录密码" wd="loginFakePwd">
		<input type="password" name="pwdv" id="pwdvc" style="display:none;color:#666"  wd="loginRealPwd">
		<input type="hidden" name="pwd" id="pwdc">
		</div>
		<div class="reghint">
		<div>
		<img src="<?php p(webhost.'include/plugin/captcha/?'.time())?>" id="captchaimg" width="97px" height="24px"  alt="请点击刷新验证码" title="点击刷新验证码" onClick="this.src=this.src;"/>
		<input type="text" name="captcha" id="captcha" value="请输入验证码" title="请输入验证码" wd="hint" style="color:#999">
		<input class="nlsubmit" style="margin-left:0" type="button" value="不登录提交" >
		</div>
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
		$("#pwdc").val(hex_md5($("#pwdvc").val()));
	});
	$("[wd='closeCaptcha']").click(function(){
		$("#popupC").hide(500);
		$(".isubmit").val('提交'); 
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
	$(".nlsubmit").click ( function () {
		$(".nlsubmit").val('提交中...'); 
		if($("captcha").val()==''){
			alert("请输入验证码");
			$(".nlsubmit").val('不登录提交'); 
		}
		else{
			$.ajax({
			url: '<?php p($webinfo["webhost"].'include/ajax/complain.php');?>',
			type: 'POST',
			data:{ cap: $("#captcha").val(),complain: $("#ipod").val(), tags: $("#tagarray").val(),from: "0", uid: "<?php p($userPageArray['uid']);?>" },
			dataType: 'html',
			timeout: 6000,
			error: function(){
				$(".nlsubmit").val('不登录提交'); 
			},
			success: function(html){
				if(html=='captcha'){
					alert('验证码错误');
					document.getElementById('captchaimg').src="<?php p(webhost.'include/plugin/captcha/?'.time())?>";   
					$(".nlsubmit").val('不登录提交'); 
				}
				else{
				$("#popupC").hide(500);
				$("#complainform").html(html);
				}
			}
			});
		}
	});
});
</script>
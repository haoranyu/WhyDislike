<strong>邀请好友</strong>
	<form method="post">
	<input name="invMail" type="text" wd="hint" title="请输入一个你的好友的邮箱地址..." value="请输入一个你的好友的邮箱地址..." style="height:24px;width:200px;color:#999" >
	<input type="submit" name="inv" value="发送邀请" style="height:28px;color:#eee;width:60px;background:#4A5E65;border:#073F4D 1px solid;">
	</form>
<strong>社交邀请</strong>
<!-- Baidu Button BEGIN -->
	<div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare"  data="{'url':'http://whydislike.com/u/<?php p($_SESSION['user']['uid']);?>'}">
		<a class="bds_qzone"></a>
		<a class="bds_tsina"></a>
		<a class="bds_tqf"></a>
		<a class="bds_tqq"></a>
		<a class="bds_renren"></a>
		<span class="bds_more"></span>
		
	</div>
<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=13292" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
	//在这里定义bds_config
	var bds_config = {<?php getShareHead($_SESSION['user']['uid']);?>'snsKey':{'renren':'cd32142dcaa148708d91f5686831a3a9'},'bdComment':'亲们，快来WhyDislike 帮助我指出缺点吧，你们可以放心，我的内心是很强大的！你们要成全我“做更好的自己”的愿望啊！','bdText':'亲们，快来@WhyDislike 帮助我指出缺点吧，你们可以放心，我的内心是很强大的！你们要成全我“做更好的自己”的愿望啊！'};
	document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
<!-- Baidu Button END -->
<?php
if(isset($_POST['inv'])){
$email = $_POST['invMail'];
$title = $_SESSION['user']['name']."邀请你帮其指出缺点";
$content = '<body style="border-width:0;margin:12px;"lang="en"style="background-color:#fff; color: #222"><div style="padding:14px; margin-bottom:4px; background-color:#072D3A; -moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px"><img src="'.webhost.'img/logo_sml.jpg"style="display:block;border: 0;" height="30px" width="129px"/></div><div style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif; font-size:13px; margin: 14px; position:relative"><h2 style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif;margin:0 0 16px; font-size:18px; font-weight:normal">'.$_SESSION['user']['name'].'邀请你帮其指出缺点</h2><p>'.$_SESSION['user']['name'].'加入了WhyDislike，发这个邮件来邀请你为其指出不足，以便其进行改正。不要吝啬你的文字，来写下其不足吧……</p><p>请访问：<a target="_blank" href="'.webhost.'u/'.$_SESSION['user']['uid'].'">'.webhost.'u/'.$_SESSION['user']['uid'].'</a></p></div></body>';
postmail($email,$email,$title,$content);
echo 'alert("您已经成功发送邀请邮件至'.$email.'");';
}
?>
$("[wd='hint']").focus(function(){
	$(this).val('');
	$(this).css("color","#666");
});
$("[wd='hint']").blur(function(){
	if($(this).val()==''){
	$(this).val($(this).attr("title"));
	$(this).css("color","#999");
	}
});
</script>


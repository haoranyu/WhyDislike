<?php
include("../core.php");
include("../db.php");
function getUser($email){
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$email."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$user = mysql_fetch_array($query);
	return $user;
}

$email = qa($_POST['email']);
if(getUser($email)==false){
	echo '你输入的email还没注册，你找什么密码啊！';
	exit;
}
else{
echo $email;
	$user = getUser($email);
	$pwd = $user['password'];
	$key = $user['email'].date('Ymd',time());
	$authcode = authcode($pwd,'ENCODE',$key,7200);
	$title = "重新设置WhyDislike的密码";
	$content = '<body style="border-width:0;margin:12px;"lang="en"style="background-color:#fff; color: #222"><div style="padding:14px; margin-bottom:4px; background-color:#072D3A; -moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px"><img src="'.webhost.'img/logo_sml.jpg"style="display:block;border: 0;" height="30px" width="129px"/></div><div style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif; font-size:13px; margin: 14px; position:relative"><h2 style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif;margin:0 0 16px; font-size:18px; font-weight:normal">重置密码</h2><p>亲爱的'.$user['name'].'：</p><p>你刚刚在WhyDislike发出了重置密码的需求。如果这不是你发出的需求，请忽略这封邮件。</p><p>请点击以下链接进行登录并重置你的密码：<br/><a href="'.webhost.'?uemail='.$email.'&authcode='.$authcode.'" target="_blank">'.webhost.'?uemail='.$email.'&authcode='.$authcode.'</a></p><p>WhyDislike运营团队</p></div></body>';
	postmail($email,$email,$title,$content);
	echo '重置邮箱的邮件已经发到了你的邮箱，请在两小时内查收。';
	exit;
}


?>
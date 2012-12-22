<?php
function getInvite($code){
	$query = mysql_query("SELECT * FROM wh_invite WHERE code = '".$code."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		return true;
	}
}
function getEmail($email){
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$email."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		return true;
	}
}
function getUid($email){
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$email."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		$row = mysql_fetch_array($query);
		return $row['uid'];
	}
}
function deleteInvite($code){
	$query = "DELETE FROM wh_invite WHERE code = '".$code."'";
	mysql_query($query);
}
function isSame($pwd,$pwd2){
	if($pwd==$pwd2){
		return true;
	}
	else{
		return false;
	}
}
function postReg($email,$name,$pwd,$code){
	$query = "INSERT INTO `wh_user`(`email`, `name`,`sex`, `count`, `correct`, `fans`, `att`, `password`, `description`, `province`,`city`, `birthday`, `verify`, `alert`, `bg`) VALUES ('".$email."','".$name."','1','0','0','0','0','".$pwd."','','','','0','0','0','0')";
	mysql_query($query);
	deleteInvite($code);
}
function verifyMail($email){
	$query = "UPDATE wh_user SET verify = '1',alert = '1' WHERE email = '".$email."'";
	mysql_query($query);//写update函数
	//插入被动关注
	$uid = getUid($email);
	$query="INSERT INTO wh_friend (uid, fuid) VALUES ('1','".$uid."')";
	//此处代码是否可行需要上线验证
	mysql_query($query);
}
$isUserpage = 1;
//已有token登录状态跳转
if(isset($_SESSION['token'])){
	header("location:".$webinfo["webhost"]."center/");
	exit;
}
if(isset($_GET['verify'])){
	$verify = qa(str_replace(' ','+',$_GET['verify']));
	$key = date('Ymd',time());
	$email = authcode($verify,'DECODE',$key,86400);
	verifyMail($email);
	header("location:".webhost.'?note=verify&uemail='.$email);
	exit;
}
if(isset($_GET['code'])){
	$code = qa($_GET['code']);
}
else{
	$code = '';
}
if($code != ''&&!getInvite($code)){
header("location:".webhost.'?note=invcode');
}
if(isset($_POST['reg'])){
	$code = qa($_POST['code']);
	if(getInvite($code)){
		$email = qa($_POST['email']);
		if(getEmail($email)){
			header("location:".webhost.'?note=email');
			exit;
		}
		$name = qa($_POST['name']);
		$pwd = qa($_POST['pwd']);
		$pwd2 = qa($_POST['pwd2']);
		if(isSame($pwd,$pwd2)){
			postReg($email,$name,$pwd,$code);
			//post verify mail!
			$key = date('Ymd',time());
			$verify = authcode($email,'ENCODE',$key,86400);
			$title = "激活WhyDislike的账号";
			$content = '<body style="border-width:0;margin:12px;"lang="en"style="background-color:#fff; color: #222"><div style="padding:14px; margin-bottom:4px; background-color:#072D3A; -moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px"><img src="'.webhost.'img/logo_sml.jpg"style="display:block;border: 0;" height="30px" width="129px"/></div><div style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif; font-size:13px; margin: 14px; position:relative"><h2 style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif;margin:0 0 16px; font-size:18px; font-weight:normal">激活账号</h2><p>亲爱的'.$name.'：</p><p>你刚刚在WhyDislike注册了账号，需要激活才可以使用。</p><p>请点击以下链接进行激活：<br/><a href="'.webhost.'verify/'.$verify.'/" target="_blank">'.webhost.'verify/'.$verify.'/</a></p><p>WhyDislike运营团队</p></div></body>';
			postmail($email,$email,$title,$content);
			header("location:".webhost.'?note=reg');
			exit;
		}
		else{
		header("location:".webhost.'?note=pwdd');
		exit;
		}
	}
	else{
	header("location:".webhost.'?note=invcode');
	exit;
	}
}
?>
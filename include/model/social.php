<?php
function getSocial($mid){
	$media = explode('_',$mid);
	$query = mysql_query("SELECT * FROM wh_social WHERE mid = '".$media[0]."' AND muid = '".$media[1]."' LIMIT 1");
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
function isSame($pwd,$pwd2){
	if($pwd==$pwd2){
		return true;
	}
	else{
		return false;
	}
}
/*
function postReg($email,$name,$pwd,$mid){
	$query = "INSERT INTO `wh_user`(`email`, `name`, `count`, `correct`, `fans`, `att`, `password`, `description`, `verify`, `alert`, `bg`) VALUES ('".$email."','".$name."','0','0','0','0','".$pwd."','','1','0','0')";
	mysql_query($query);
	
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$email."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		$user = mysql_fetch_array($query);
		$uid = $user['uid'];
		$query = "UPDATE wh_renren SET uid = '".$uid."' WHERE mid = '".$mid."'";
		mysql_query($query);//写update函数
	}
}
*/
function updateReg($email,$pwd,$uid){
	$query = "UPDATE `wh_user` SET email = '".$email."',password = '".$pwd."' WHERE uid = '".$uid."'";
	mysql_query($query);
}
$isUserpage = 1;
//已有token登录状态跳转
if(isset($_SESSION['token'])){
	header("location:".$webinfo["webhost"]."center/");
	exit;
}
if(isset($_GET['mid'])){
	$mid = qa($_GET['mid']);
}
if(isset($_POST['reg'])){
	$mid = qa($_POST['mid']);
	if(getSocial($mid)!=false){
		$email = qa($_POST['email']);
		if(getEmail($email)){
			header("location:".webhost.'?note=email');
			exit;
		}
		$name = '';
		$pwd = qa($_POST['pwd']);
		$pwd2 = qa($_POST['pwd2']);
		if(isSame($pwd,$pwd2)){
			updateReg($email,$pwd,getSocial($mid));
			//post verify mail!
			$key = date('Ymd',time());
			$verify = authcode($email,'ENCODE',$key,86400);
			$title = "激活WhyDislike的账号";
			$content = '<body style="border-width:0;margin:12px;"lang="en"style="background-color:#fff; color: #222"><div style="padding:14px; margin-bottom:4px; background-color:#072D3A; -moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px"><img src="'.webhost.'img/logo_sml.jpg"style="display:block;border: 0;" height="30px" width="129px"/></div><div style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif; font-size:13px; margin: 14px; position:relative"><h2 style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif;margin:0 0 16px; font-size:18px; font-weight:normal">激活账号</h2><p>亲爱的'.$name.'：</p><p>你刚刚在WhyDislike注册了账号，需要激活才可以使用。</p><p>请点击以下链接进行激活：<br/><a href="'.webhost.'verify/'.$verify.'/" target="_blank">'.webhost.'verify/'.$verify.'/</a></p><p>WhyDislike运营团队</p></div></body>';
			postmail($email,$email,$title,$content);
			header("location:".webhost.'?note=renren');
			exit;
		}
		else{
		header("location:".webhost.'?note=pwdd');
		exit;
		}
	}
	else{
	header("location:".webhost.'?note=email');
	}
/*	else{
		if(getEmail('rr'.$_POST['mid'])){//这层判断是临时策略，2012年12月31去去除
			$email = qa($_POST['email']);
			if(getEmail($email)){
				header("location:".webhost.'?note=email');
				exit;
			}
			$pwd = qa($_POST['pwd']);
			$pwd2 = qa($_POST['pwd2']);
			if(isSame($pwd,$pwd2)){
				updateReg($email,$pwd,$mid);
				//post verify mail!
				$key = date('Ymd',time());
				$verify = authcode($email,'ENCODE',$key,86400);
				$title = "激活WhyDislike的账号";
				$content = '<body style="border-width:0;margin:12px;"lang="en"style="background-color:#fff; color: #222"><div style="padding:14px; margin-bottom:4px; background-color:#072D3A; -moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px"><img src="'.webhost.'img/logo_sml.jpg"style="display:block;border: 0;" height="30px" width="129px"/></div><div style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif; font-size:13px; margin: 14px; position:relative"><h2 style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif;margin:0 0 16px; font-size:18px; font-weight:normal">激活账号</h2><p>亲爱的'.$name.'：</p><p>你刚刚在WhyDislike注册了账号，需要激活才可以使用。</p><p>请点击以下链接进行激活：<br/><a href="'.webhost.'verify/'.$verify.'/" target="_blank">'.webhost.'verify/'.$verify.'/</a></p><p>WhyDislike运营团队</p></div></body>';
				postmail($email,$email,$title,$content);
				header("location:".webhost.'?note=renren');
				exit;
			}
		}
		else{
			header("location:".webhost.'?note=email');
			exit;
		}
	}
*/
}
?>
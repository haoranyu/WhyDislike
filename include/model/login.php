<?php
//设置Session函数
function setSession($name,$info){
	$_SESSION[$name]=$info;
	return 1;
}
//邮箱验证函数
function verifyMail($email){
	$query = "UPDATE wh_user SET verify = '1' WHERE email = '".$email."'";
	mysql_query($query);//写update函数
}
//登录验证函数
function getLogin($email,$password){
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$email."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$user = mysql_fetch_array($query);
	if($user["password"] == $password){
		return $user;
	}
}
//获取Token函数
function getToken($date,$username){
	$token = rand(10,99).$date.md5($username);
	return $token;
}
function getVerify(){
	$array = array();
	$query = mysql_query("select   *   from   wh_verify   order   by   RAND()   limit   4 ");
	while($row = mysql_fetch_array($query)){
		$queryin = mysql_query("select   *   from   wh_user   where uid = '".$row['uid']."'   limit   1 ");
		$rowin = mysql_fetch_array($queryin);
		$rowin['description']=$row['info'];
		array_push($array,$rowin);
	}
	return $array;
}
//获得头像函数
function getHead($uid){
	if(file_exists('img/head/large/'.$uid.'.jpg')){
		echo '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/large/'.$uid.'.jpg"/></a>';
	}
	else{
		echo '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/large/0.jpg"/></a>';
	}
}
//已有token登录状态跳转
if(isset($_SESSION['token'])){
	header("location:".$webinfo["webhost"]."center/");
	exit;
}
//使用authcode进行密码重置
elseif(isset($_GET['authcode'])){
	$email = qa($_GET['uemail']);
	$authcode = qa(str_replace(' ','+',$_GET['authcode']));
	$key = $email.date('Ymd',time());
	$password = authcode($authcode,'DECODE',$key,7200);
	if(getLogin($email,$password)){
		$token = getToken(time(),$email);
		verifyMail($email);
		setSession("token",$token);
		setSession("email",$email);
		setSession("user",getLogin($email,$password));
		setActTime($_SESSION['user']['uid']);
		$isLogin = 1;
		header("location:".webhost.'setting/2/'.$authcode.'/');//跳转到密码页面
		exit;
	}
	else{
		header("location:".$webinfo["webhost"].'?note=fgt');
	}
}
//错误处理
else{
	$email = '';
	if(isset($_GET['note'])){
		$error = $_GET['note'];
		switch($error){
			case "reg":$alert='注册成功，请在24小时内去你的邮箱激活账号。';break;
			case "renren":$alert='注册成功，请使用人人登录或者激活邮箱后用邮箱账号登录。';break;
			case "pwdd":$alert='两次密码不匹配，请返回重新注册。';break;
			case "invcode":$alert='您填写的邀请码无效，暂时无法注册。';break;
			case "repwd":$alert='密码修改成功，请重新登录。';break;
			case "pwd":$alert='密码错误，请重试。';break;
			case "email":$alert='您使用的Email已经注册过了，请直接登录。';break;
			case "fgt":$alert='你的密码重置请求已经过期。';break;
			case "verify":$alert='你的账号已经验证激活成功，请重新登录。';break;
			case "network":$alert='网络故障，通信失败，请稍后再试。';break;
		}
		if(isset($_GET['uemail'])){
			$email = $_GET['uemail'];
		}
	}
}
?>
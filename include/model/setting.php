<?php
//设置Session序列
function setSessionArray($name,$info){
	$length = sizeof($name);
	for($i = 0; $i < $length; $i++){
		$_SESSION['user'][$name[$i]]=$info[$i];
	}
	return true;
}
//获得人人绑定信息
function getSocial($mid,$uid){
	$query = mysql_query("SELECT * FROM wh_social WHERE mid = '".$mid."' AND uid = '".$uid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	return true;
}
//获得域名信息
function getDomain($uid){
	$query = mysql_query("SELECT * FROM wh_domain WHERE uid = '".$uid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$row = mysql_fetch_array($query);
	return $row['domain'];
}
//更新用户信息
function updateInfo($name,$value,$uid){
	$length = sizeof($name);
	$string ='';
	for($i = 0; $i < $length; $i++){
		if($i==0){
		$string .= ($name[$i]." = '".$value[$i]."'");
		}
		else{
		$string .= (",".$name[$i]." = '".$value[$i]."'");
		}
	}
	$query = "UPDATE wh_user SET ".$string." WHERE uid = '".$uid."'";
	if(mysql_query($query)){
		setSessionArray($name,$value);
		return true;
	}
	else{
		return false;
	}
}
//更新域名信息
function setDomain($domain,$uid){
	$query = mysql_query("SELECT * FROM wh_domain WHERE uid = '".$uid."' OR domain='".$domain."' LIMIT 1");
	if(mysql_num_rows($query)==0){
		$insert=true;
	}
	else{
		$insert=false;
	}
	if($insert){
		$query_insert = mysql_query("INSERT INTO wh_domain (uid, domain) VALUES ('".$uid."','".$domain."')");
		if(mysql_query($query_insert)){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}
function makeTime($time){
	$arr = explode("-",$time);
	return mktime(0,0,0,$arr[1],$arr[2],$arr[0]);
}
//用户修改选项卡哦安定
if(isset($_GET['tab'])){
	$tab = $_GET['tab'];
}
else{
	$tab = '1';
}
//是否为没有登录状态判断
if(!$isLogin){
	header("location:".$webinfo["webhost"].'?next='.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
}
//form
$modify = 1;
$domain_repeat=0;
if(isset($_POST['save1'])){
	if($_POST['email']==$_SESSION['user']['email']){
	//修改邮件地址需要邮件确认修改操作，待添加
	}
	$name = qa(subString($_POST['name'],0,7,''));
	$description = qa($_POST['description']);
	$sex = qa($_POST['sex']);
	$province = qa($_POST['province']);
	$city = qa($_POST['city']);
	$birthday = makeTime(qa($_POST['birthday']));
	$alert = qa($_POST['alert']);
	$thide = qa($_POST['thide']);
	updateInfo(array('name','sex','description','province','city','birthday','alert','thide'),array($name,$sex,$description,$province,$city,$birthday,$alert,$thide),$_SESSION['user']['uid']);
}
elseif(isset($_POST['save2'])){
	$key1 = $_POST['keyword'];
	$key2 = $_POST['newkey'];
	$key3 = $_POST['renewkey'];
	if($key2==$key3&&($key1==$_SESSION['user']['password'])){
		updateInfo(array('password'),array($key2),$_SESSION['user']['uid']);
		unset($_SESSION['token']);
		header("location:".$webinfo["webhost"]."?note=repwd");
	}
	else{
		$modify = 0;
	}
}
elseif(isset($_POST['save4'])){
	$bg = qa($_POST['bg'])%9;
	updateInfo(array('bg'),array($bg),$_SESSION['user']['uid']);
}
elseif(isset($_POST['save6'])){
	if(false==setDomain(qa($_POST['domain']),$_SESSION['user']['uid'])){
		$domain_repeat=1;
		$modify = 0;
	}
	else{
		$modify = 1;
	}
}
else{
	$modify = 0;
}
if(isset($_GET['authcode'])){
	$authcode = qa(str_replace(' ','+',$_GET['authcode']));
	$key = $_SESSION['user']['email'].date('Ymd',time());
	$password = authcode($authcode,'DECODE',$key,7200);
}
?>
<?php
include("../../core.php");
include("../../db.php");
header("Content-type:text/html;charset=utf-8");

if(!$isLogin){
	header("location:".$webinfo["webhost"]);
	exit;
}

//加载文件
require_once 'denglu.php';

//初始化接口类Denglu
$api = new Denglu('13077denEFilsc88fX107xGffrSbj3','$1$75851den$wRvj3M7cv8mSGEB6a9I/b1','utf-8');

//写入绑定
function insertUser($uid,$mid,$muid,$url){
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$mid."_".$muid."' LIMIT 1");
	//return "SELECT * FROM wh_user WHERE email = '".$mid."_".$muid."' LIMIT 1";
	$flag=1;
	if($query==FALSE){
		$flag=0;
	}
	if(mysql_num_rows($query)==0){
		$flag=0;
	}
	if($url == ''){
		$url = '#';
	}
	if(	$flag==1){
	$query = "UPDATE wh_social SET uid = '".$uid."',url = '".$url."' WHERE mid = '".$mid."' AND muid = '".$muid."'";
	mysql_query($query);
	}
	else{
	$query = "INSERT INTO `wh_social`(`mid`, `muid`, `uid`, `url`)  VALUES ('".$mid."','".$muid."','".$uid."','".$url."')";
	mysql_query($query);
	}
	return true;
}

/*
 *发送绑定请求
 */
if(!empty($_GET['token'])){
	try{
		$userInfo = $api->getUserInfoByToken($_GET['token']);
		$mid = $userInfo['mediaID'];
		$muid = $userInfo['mediaUserID'];
		$uid = $_SESSION['user']['uid'];
		$url = ($userInfo['homepage']=='')?$userInfo['url']:$userInfo['homepage'];
		insertUser($uid,$mid,$muid,$url);
		header("location:".webhost.'setting/5');
	}catch(DengluException $e){//获取异常后的处理办法(请自定义)
		//return false;		
		//echo $e->geterrorCode();  //返回错误编号
		//echo $e->geterrorDescription();  //返回错误信息
	}
}


?>
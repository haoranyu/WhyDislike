<?php
include("../../core.php");
include("../../db.php");
header("Content-type:text/html;charset=utf-8");

if(!$isLogin){
	header("location:".$webinfo["webhost"]);
	exit;
}

//�����ļ�
require_once 'denglu.php';

//��ʼ���ӿ���Denglu
$api = new Denglu('13077denEFilsc88fX107xGffrSbj3','$1$75851den$wRvj3M7cv8mSGEB6a9I/b1','utf-8');

//д���
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
 *���Ͱ�����
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
	}catch(DengluException $e){//��ȡ�쳣��Ĵ���취(���Զ���)
		//return false;		
		//echo $e->geterrorCode();  //���ش�����
		//echo $e->geterrorDescription();  //���ش�����Ϣ
	}
}


?>
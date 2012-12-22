<?php
require_once '../../core.php';
require_once '../../db.php';

if(!$isLogin){
	header("location:".$webinfo["webhost"]);
	exit;
}


//加载文件
require_once 'denglu.php';

//初始化接口类Denglu
$api = new Denglu('13077denEFilsc88fX107xGffrSbj3','$1$75851den$wRvj3M7cv8mSGEB6a9I/b1','utf-8');

function getBind($mid,$uid){
	$query = mysql_query("SELECT * FROM wh_social WHERE mid = '".$mid."' AND $uid = '".$muid."' LIMIT 1");
	if($query==FALSE){
		$flag = 0;
	}
	if(mysql_num_rows($query)==0){
		$flag = 0;
	}
	$user = mysql_fetch_array($query);
	return $user['muid'];
}

if(isset($_GET['website'])){
	if('renren'==$_GET['website']){
	$query = "DELETE FROM wh_social WHERE mid = '7' AND uid = '".$_SESSION['user']['uid']."' ";
	mysql_query($query);
	}
	if('qzone'==$_GET['website']){
	$query = "DELETE FROM wh_social WHERE mid = '13' AND uid = '".$_SESSION['user']['uid']."' ";
	mysql_query($query);
	}
	if('baidu'==$_GET['website']){
	$query = "DELETE FROM wh_social WHERE mid = '19' AND uid = '".$_SESSION['user']['uid']."' ";
	mysql_query($query);
	}
	if('weibo'==$_GET['website']){
	$query = "DELETE FROM wh_social WHERE mid = '3' AND uid = '".$_SESSION['user']['uid']."' ";
	mysql_query($query);
	}
}
header("location:".webhost.'setting/5');
?>
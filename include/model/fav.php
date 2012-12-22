<?php
//Session设置函数
function setSession($name,$info){
	$_SESSION[$name]=$info;
	return 1;
}
//Token获取函数
function getToken($date,$username){
	$token = rand(10,99).$date.md5($username);
	return $token;
}
//获得用户名函数
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
function getNotify($uid){
	$query = mysql_query("SELECT * FROM `wh_notify` WHERE `uid` = '".$uid."' AND `read` = 0");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		return mysql_num_rows($query);
	}
}
//获得分享头像函数
function getShareHead($uid){
	if(file_exists('img/head/large/'.$uid.'.jpg')){
		echo "'bdPic':'".webhost.'img/head/large/'.$uid.'.jpg'."',";
	}
	else{
		echo '';
	}
}
//获得用户描述函数
function getDescription($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['description'];
}
//获得标签列表字符串函数
function getTag($cid){
	$tag_select = mysql_query("SELECT * FROM wh_complain_tag WHERE cid = '".$cid."'");
	$array = array();
	while($row = mysql_fetch_array($tag_select)){
		array_push($array,$row['tid']);
	}
	$tagString='';
	$arrayLength = sizeof($array);
	foreach($array as $tid){
		$query = mysql_query("SELECT * FROM wh_tag WHERE tid = '".$tid."' LIMIT 1");
		$tag = mysql_fetch_array($query);
		$tagString .= '<a href="'.webhost.'tag/'.$tag['tid'].'">'.$tag['tag'].'</a>';
	}
	return $tagString;
}
//获得实时粉丝数函数
function getFanscount($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['fans'];
}
//获得Dislike列表函数
function getDislike($uid,$page=1){
	$start = ($page-1)*15;
	$query = mysql_query("SELECT * FROM wh_fav WHERE uid = '".$uid."' ORDER BY id DESC LIMIT ".$start.",15");
	$array = array();
	while($row = mysql_fetch_array($query,MYSQL_ASSOC)){
		$query2 = mysql_query("SELECT * FROM wh_complain WHERE cid = '".$row['cid']."' LIMIT 1");
		$row2 = mysql_fetch_array($query2,MYSQL_ASSOC);
		array_push($array,$row2);
	}
	return $array;
}
//获得Dislike分页导航栏函数
function getDislikePagebar($uid,$page){
	$query = mysql_query("SELECT * FROM wh_fav WHERE uid = '".$uid."'");
	$maxPage = (mysql_num_rows($query)/15);
	$pageString = '';
	if($maxPage<=1){
	return '';
	}
	for($i=0;$i<$maxPage;$i++){
		$pagenum = $i+1;
		if($pagenum==$page){
			$pageString .= '<span>'.$pagenum.'</span>';
		}
		else{
			$pageString .= '<a href="'.webhost.'fav/page/'.$pagenum.'">'.$pagenum.'</a>';
		}
	}
	return '<div class="pagebar">'.$pageString.'</div>';
}
//获得头像函数
function getHead($uid,$size){
	if($size=='s'){
		if(file_exists('img/head/small/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/small/'.$uid.'.jpg"/>';
		}
		else{
			echo '';
		}
	}
	elseif($size=='m'){
		if(file_exists('img/head/medium/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/medium/'.$uid.'.jpg"/>';
		}
		else{
			echo '';
		}
	}
	elseif($size=='l'){
		if(file_exists('img/head/large/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/large/'.$uid.'.jpg"/>';
		}
		else{
			echo '';
		}
	}
	else{
		echo '';
	}
}
//
if((!$isLogin)){
	header("location:".$webinfo["webhost"].'?next='.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit;
}
//pagebar
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
else{
	$page = 1;
}
//Dislike type
$dislikeList = getDislike($_SESSION['user']['uid'],$page);
$pagebar = getDislikePagebar($_SESSION['user']['uid'],$page);
?>
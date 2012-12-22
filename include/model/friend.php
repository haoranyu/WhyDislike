<?php
//获得用户名函数
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
//获得用户描述函数
function getDescription($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['description'];
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
//获得标签列表字符串函数
function getTag($tag){
	$array = explode(",",$tag);
	$tagString='';
	$arrayLength = sizeof($array);
	for($i=0;$i<$arrayLength;$i++){
		$query = mysql_query("SELECT * FROM wh_tag WHERE tid = '".$array[$i]."' LIMIT 1");
		$tag = mysql_fetch_array($query);
		$tagString .= '<a href="'.$tag['tag'].'">'.$tag['tag'].'</a>';
	}
	return $tagString;
}
//获得好友列表函数
function getFriend($uid,$page=1,$att = 1){
	$start = ($page-1)*10;
	if($att){
	$query = mysql_query("SELECT * FROM wh_friend WHERE uid = '".$uid."' ORDER BY fid DESC LIMIT ".$start.",10");
	}
	else{
	$query = mysql_query("SELECT * FROM wh_friend WHERE fuid = '".$uid."'  ORDER BY fid DESC LIMIT ".$start.",10");
	}
	$array = array();
	while($row = mysql_fetch_array($query,MYSQL_ASSOC)){
		array_push($array,$row);
	}
	return $array;
}
//获得粉丝数函数
function getFanscount($uid){//特别添加一个临时函数在这里
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['fans'];
}
//获得好友分页条
function getFriendPagebar($uid,$page,$att = 1){
	$host = 'http://whydislike.com/';
	if($att){
	$query = mysql_query("SELECT * FROM wh_friend WHERE uid = '".$uid."'");
	}
	else{
	$query = mysql_query("SELECT * FROM wh_friend WHERE fuid = '".$uid."'");
	}
	$tempPage = (mysql_num_rows($query)/10);
	if($tempPage<=15){
		$maxPage = $tempPage;
		$minPage = 0;
	}
	else{
		$minPage = $page - 7;
		$maxPage = $page + 7;
		if($minPage<0){
			$maxPage = $maxPage - $minPage;
			$minPage = 0;
		}
		elseif($maxPage>$tempPage){
			$maxPage = $tempPage;
			$minPage = $minPage + ($tempPage-$maxPage);
		}
	}
	$pageString = '';
	if($maxPage<=1){
	return '';
	}
	for($i=$minPage;$i<=$maxPage;$i++){
		$pagenum = $i+1;
		if($pagenum==$page){
			$pageString .= '<span>'.$pagenum.'</span>';
		}
		else{
			if(!$att){
			$pageString .= '<a href="'.$host.'friend/page/'.$pagenum.'">'.$pagenum.'</a>';
			}
			else{
			$pageString .= '<a href="'.$host.'att/page/'.$pagenum.'">'.$pagenum.'</a>';
			}
		}
	}
	return '<div class="pagebar">'.$pageString.'</div>';
}
//获得头像函数
function getHead($uid,$size,$pix = 0){
	if($size=='s'){
		if(file_exists('img/head/small/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/small/'.$uid.'.jpg" width="'.$pix.'px" height="'.$pix.'px"/>';
		}
		else{
			echo '';
		}
	}
	elseif($size=='m'){
		if(file_exists('img/head/medium/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/medium/'.$uid.'.jpg" width="'.$pix.'px" height="'.$pix.'px"/>';
		}
		else{
			echo '';
		}
	}
	elseif($size=='l'){
		if(file_exists('img/head/large/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/large/'.$uid.'.jpg" width="'.$pix.'px" height="'.$pix.'px"/>';
		}
		else{
			echo '';
		}
	}
	else{
		echo '';
	}
}
//判断是否是未登录状态
if(!$isLogin){
	header("location:".$webinfo["webhost"].'?next='.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
}
//判断是否为关注列表
if(isset($_GET['att'])){
	$att = intval($_GET['att']);
}
else{
	$att = 1;
}
//判断当前页面位置
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
else{
	$page = 1;
}
//Friend type
if($att){
	$firendList = getFriend($_SESSION['user']['uid'],$page);
	$pagebar = getFriendPagebar($_SESSION['user']['uid'],$page);
}
else{
	$firendList = getFriend($_SESSION['user']['uid'],$page,0);
	$pagebar = getFriendPagebar($_SESSION['user']['uid'],$page,0);
}
//操作消息处理
modifynote($_SESSION['user']['uid'], -1);
?>
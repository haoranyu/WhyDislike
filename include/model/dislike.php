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
//获得粉丝数函数
function getFanscount($uid){//特别添加一个临时函数在这里
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['fans'];
}
//获得头像函数
function getHead($uid,$size,$pix = 0){
	if($size=='s'){
		if(file_exists('img/head/small/'.$uid.'.jpg')&&$uid!=0){
			echo '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/small/'.$uid.'.jpg" /></a>';
		}
		elseif($uid!=0){
			echo '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/small/0.jpg" /></a>';
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
function getFav($uid,$cid){
	$query = mysql_query("SELECT * FROM wh_fav WHERE uid = '".$uid."' AND cid = '".$cid."' LIMIT 1");
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
function getComplain($cid){
	$query = mysql_query("SELECT * FROM wh_complain WHERE cid = '".$cid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		$complain = mysql_fetch_array($query);
		return $complain;
	}
}
function getComment($cid){
	$query = mysql_query("SELECT * FROM wh_reply WHERE cid = '".$cid."' ORDER BY time ASC");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		$array = array();
		while($row = mysql_fetch_array($query)){
			array_push($array,$row);
		}
		return $array;
	}
}
function ajaxComment($cid,$part=1){
	if($part==1){
	p('<li>');
		p('<div class="left">');
		getHead($_SESSION['user']['uid'],"s",48);
		p('</div><div class="right">');
			p('<div class="f12 namex"><span><small class="f12 b dark">'.$_SESSION['user']['name'].'</small>'.$_SESSION['user']['description'].'</span><span class="time">'.date("Y-m-d h:i:s",time()).'</span></div>');
			p('<div class="content f12">');
	}else{
		p('</div></div>');
		p('<div class="clear"></div>');
	p('</li>');
	}
}
//判断是否是未登录状态
if(!$isLogin){
	header("location:".$webinfo["webhost"].'?next='.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
}
//判断当前页面位置
if(isset($_GET['cid'])){
	$cid = $_GET['cid'];
}
else{
	$cid = 1;
}
//
if(getComplain($cid)!=false){
	$complain = getComplain($cid);
}else{
	header("location:".$webinfo["webhost"]);
	exit;
}
//操作消息处理
modifynote($_SESSION['user']['uid'], $cid);
$commentList = getComment($cid);
?>
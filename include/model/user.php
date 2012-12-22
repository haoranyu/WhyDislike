<?php
function getUserArray($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user;
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
//获得用户的字符串
function getUserTag($uid){
	$tag_select = mysql_query("SELECT * FROM wh_user_tag WHERE uid = '".$uid."' LIMIT 3");
	$array = array();
	while($row = mysql_fetch_array($tag_select)){
		array_push($array,$row['tid']);
	}
	$tagString='';
	$arrayLength = sizeof($array);
	foreach($array as $tid){
		$query = mysql_query("SELECT * FROM wh_tag WHERE tid = '".$tid."' LIMIT 1");
		$tag = mysql_fetch_array($query);
		$tagString .= '<a href="'.webhost.'tag/'.$tag['tid'].'">'.$tag['tag'].'</a> ';
	}
	return $tagString;
}
//获得评论
function getComment($cid){
	$init = mysql_query("SELECT * FROM wh_complain WHERE cid = '".$cid."' LIMIT 1");
	$row_init = mysql_fetch_array($init);
	$query = mysql_query("SELECT * FROM wh_reply WHERE cid = '".$cid."' AND ruid = '".$row_init['uid']."' ORDER BY time ASC LIMIT 2");
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
function getDislike($uid,$thide){
	if($thide=='1'){
	$query = mysql_query("SELECT * FROM wh_complain WHERE uid = '".$uid."' AND correct = '1' ORDER BY ctime DESC,cid DESC LIMIT 15");
	}
	else{
	$query = mysql_query("SELECT * FROM wh_complain WHERE uid = '".$uid."' ORDER BY ctime DESC,cid DESC LIMIT 15");
	}$array = array();
	while($row = mysql_fetch_array($query,MYSQL_ASSOC)){
		if($row['correct']=='1'){
			$row['time']=$row['ctime'];//以修改时间代替时间在数组中用于排序
		}
		array_push($array,$row);
	}
	$arrField = array_map(create_function('$n', 'return $n["time"];'), $array);
	array_multisort($arrField, SORT_DESC, $array);
	return $array;
}
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
function getRelation($uid,$fuid){
	$query = mysql_query("SELECT * FROM wh_friend WHERE uid = '".$uid."' AND fuid = '".$fuid."' LIMIT 1");
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
function getHead($uid,$size){
	if($size=='s'){
		if(file_exists('img/head/small/'.$uid.'.jpg')&&$uid!=0){
			echo '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/small/'.$uid.'.jpg" />';
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
function getRenren($uid){
	$query = mysql_query("SELECT * FROM wh_social WHERE mid = '7' AND uid = '".$uid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$row = mysql_fetch_array($query);
	return $row['url'];
}
function getWeibo($uid){
	$query = mysql_query("SELECT * FROM wh_social WHERE mid = '3' AND uid = '".$uid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$row = mysql_fetch_array($query);
	return $row['url'];
}
function getVip($uid){
	$query = mysql_query("SELECT * FROM wh_verify WHERE uid = '".$uid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$row = mysql_fetch_array($query);
	return $row['info'];
}
function getRealDomain($domain){
	$query = mysql_query("SELECT * FROM wh_domain WHERE domain = '".$domain."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$row = mysql_fetch_array($query);
	return $row['uid'];
}
//action
if(isset($_GET['uid'])){
	$userUid = qa($_GET['uid']);
}
elseif(isset($_GET['domain'])){
	$userDomain = qa($_GET['domain']);
	if(getRealDomain($userDomain)!=false){
		$userUid = getRealDomain($userDomain);
	}
	else{
		header("location:".$webinfo["webhost"]);
	}
}
else{
	header("location:".$webinfo["webhost"]);
}
$isUserpage = 1;
$userPageArray = getUserArray($userUid);
$correctList = getDislike($userUid,$userPageArray['thide']);
$corr = array("我已改正了","我知道了，我会改进的","亲，感谢你帮我指出了不足，我会加油的");
?>
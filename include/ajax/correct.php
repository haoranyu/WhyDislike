<?php
include("../core.php");
include("../db.php");
function getComplain($cid){
	$query = mysql_query("SELECT * FROM `wh_complain` WHERE `cid` = '".$cid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		return mysql_fetch_array($query);
	}
}
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
//Correct action
if(isset($_POST['cid'])&&isset($_POST['reply'])&&isset($_POST['type'])){
	$reply = qa($_POST['reply']);
	$cid = qa($_POST['cid']);
	$type = qa($_POST['type']);
	$complain = getComplain($cid);
	if($complain['uid']!=$_SESSION['user']['uid']){
	exit;
	}
	$ccount = $_SESSION['user']['correct']++;
	$ctime = time();
	$query_c = "UPDATE wh_complain SET reply = '".$reply."',correct = '1',ctime='".$ctime."',type='".$type."' WHERE cid = '".$cid."'";
	mysql_query($query_c);
	$query_u = "UPDATE wh_user SET correct = '".$ccount."' WHERE uid = '".$_SESSION['user']['uid']."'";
	mysql_query($query_u);
//发送站内消息
	$complain = getComplain($cid);
	if(intval($complain['from'])!=0){
	$name = getName($complain['uid']);
	postnote($complain['from'],$cid,$content = '<a href="'.webhost.'dislike/'.$cid.'/" target="_blank">'.$name.' 刚刚回应了您指出的“'.subString($complain['complain'],0,14).'”的不足。</a>');
	}
}
?>
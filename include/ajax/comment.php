<?php
include("../core.php");
include("../db.php");

function getRepeat($uid,$content){
	$query = mysql_query("SELECT * FROM `wh_reply` WHERE `uid`='".$uid."' AND `content`='".$content."' ORDER BY rid DESC LIMIT 1");
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		$row = mysql_fetch_array($query);
		if((time() - intval($row['time']))>360){
			return false;
		}
		else{
			return true;
		}
	}
}
//获得用户名函数
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
function getTitle($cid){
	$query = mysql_query("SELECT * FROM wh_complain WHERE cid = '".$cid."' LIMIT 1");
	$text = mysql_fetch_array($query);
	return $text['complain'];
}
if(!(isset($_POST['cid'])&&isset($_POST['uid'])&&isset($_POST['ruid'])&&isset($_POST['content']))){
exit;
}
$cid = qa($_POST['cid']);
$uid = qa($_POST['uid']);
if($uid!=$_SESSION['user']['uid']){
	exit;
}
$ruid = qa($_POST['ruid']);
$content = qa($_POST['content']);
$time = time();

if(getRepeat($uid,$content)!=true){
	$insert = "INSERT INTO `wh_reply`(`cid`,`uid`, `ruid`, `content`,`time`) VALUES ('".$cid."','".$uid."','".$ruid."','".$content."','".$time."')";
	mysql_query($insert);
	$con = '<a href="http://whydislike.com/dislike/'.$cid.'/" target="_blank" >'.getName($uid).'关于“'.subString(getTitle($cid),0,13).'”的问题回复了您</a>';
	postnote($ruid,$cid,$con);
}
?>
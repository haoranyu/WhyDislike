<?php
include("../db.php");
include("../core.php");
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
//获得用户名函数
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
//
if(isset($_POST['uid'])&&isset($_POST['fuid'])){
	$uid = intval($_POST['uid']);
	$fuid = intval($_POST['fuid']);
	if($uid==$fuid){
	exit;
	}
	if($uid!=$_SESSION['user']['uid']){
	exit;
	}
	if(getRelation($uid,$fuid)){
		$query="DELETE FROM wh_friend WHERE uid = '".$uid."' AND fuid = '".$fuid."' ";
		mysql_query($query);
		$att = --$_SESSION['user']['att'];
		$query_att = "UPDATE wh_user SET att = '".$att."' WHERE uid = '".$uid."'";
		mysql_query($query_att);
		$query_select = mysql_query("SELECT * FROM wh_user WHERE uid = '".$fuid."' LIMIT 1");
		$row = mysql_fetch_array($query_select);
		$fans = --$row['fans'];
		$query_fans = "UPDATE wh_user SET fans = '".$fans."' WHERE uid = '".$fuid."'";
		mysql_query($query_fans);
	}
	else{
		$query="INSERT INTO wh_friend (uid, fuid) VALUES ('".$uid."','".$fuid."')";
		mysql_query($query);
		$att = ++$_SESSION['user']['att'];
		$query_att = "UPDATE wh_user SET att = '".$att."' WHERE uid = '".$uid."'";
		mysql_query($query_att);
		$query_select = mysql_query("SELECT * FROM wh_user WHERE uid = '".$fuid."' LIMIT 1");
		$row = mysql_fetch_array($query_select);
		$fans = ++$row['fans'];
		$query_fans = "UPDATE wh_user SET fans = '".$fans."' WHERE uid = '".$fuid."'";
		mysql_query($query_fans);
		$con = '<a href="http://whydislike.com/friend/" target="_blank" > '.getName($uid).' 关注了您，去问候一下吧！</a>';
		postnote($fuid,'-1',$con);
	}
}

?>
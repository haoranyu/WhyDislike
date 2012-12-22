<?php
include("../db.php");
include("../core.php");
function getRelation($uid,$tid){
	$query = mysql_query("SELECT * FROM wh_user_tag WHERE uid = '".$uid."' AND tid = '".$tid."' LIMIT 1");
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

if(isset($_POST['uid'])&&isset($_POST['tid'])){
	$uid = intval($_POST['uid']);
	$tid = intval($_POST['tid']);
	if(getRelation($uid,$tid)){
		$query = mysql_query("SELECT * FROM wh_tag WHERE tid = '".$tid."' LIMIT 1");
		$row = mysql_fetch_array($query);
		$att = --$row['att'];
		$query="DELETE FROM wh_user_tag WHERE uid = '".$uid."' AND tid = '".$tid."' ";
		mysql_query($query);
		$query="UPDATE wh_tag SET att = '".$att."' WHERE tid = '".$tid."'";
		mysql_query($query);
	}
	else{
		$query = mysql_query("SELECT * FROM wh_tag WHERE tid = '".$tid."' LIMIT 1");
		$row = mysql_fetch_array($query);
		$att = ++$row['att'];
		$query="INSERT INTO wh_user_tag (uid, tid) VALUES ('".$uid."','".$tid."')";
		mysql_query($query);
		$query="UPDATE wh_tag SET att = '".$att."' WHERE tid = '".$tid."'";
		mysql_query($query);
	}
	echo $_POST['tid'];
}

?>
<?php
include("../core.php");
include("../db.php");

function getRelation($uid,$cid){
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
if(isset($_POST['uid'])&&isset($_POST['cid'])){
	$uid = intval($_POST['uid']);
	$cid  = qa($_POST['cid']);
	if($uid!=$_SESSION['user']['uid']){
	exit;
	}
	if(getRelation($uid,$cid)){
		$query="DELETE FROM wh_fav WHERE uid = '".$uid."' AND cid = '".$cid."' ";
		mysql_query($query);
	}
	else{
		$query="INSERT INTO wh_fav (uid, cid) VALUES ('".$uid."','".$cid."')";
		mysql_query($query);
	}
}
?>
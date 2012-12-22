<?php
include("../db.php");
include("../core.php");
function getNotify($uid){
	$query = mysql_query("SELECT * FROM `wh_notify` WHERE `uid` = '".$uid."' AND `read` = 0 ORDER BY time DESC");
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
function setNotify($nid){
	$query = "UPDATE `wh_notify` SET `read` = '1' WHERE nid = '".$nid."' ";
	return mysql_query($query);
}
if(isset($_POST['nid'])){
	$nid = qa($_POST['nid']);
	$uid = qa($_POST['uid']);
	setNotify($nid);
	foreach(getNotify($uid) as $note){
		p('<li wd="notify" nid="'.$note['nid'].'">'.$note['content'].'</li>');
	}
}
else{
	$uid = qa($_POST['uid']);
	if(getNotify($uid)!=false){
		$info = array();
		foreach(getNotify($uid) as $note){
			$info_son = array(
				'nid' =>  $note['nid'],
				'content' => $note['content']
			);
			array_push($info,$info_son);
		}
		$arr = array(
		0 => true,
		1 => $info,
		2 => sizeof($info),
		);
		
	}
	else{
		$arr = array(
		0 => false
		);
	}
	echo json_encode($arr);
}
?>
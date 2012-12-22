<?php
include("include/core.php");
include("include/db.php");
function getCode(){
	$code = md5(uniqid(rand()));
	$query = mysql_query("INSERT INTO wh_invite (code) VALUES ('".$code."')");
	mysql_query($query);
	return $code;
}
if(isset($_GET['key'])&&$_GET['key']=='yhrinitkey'){
	if(isset($_GET['num'])){
		$num = intval($_GET['num']);
	}
	else{
		$num = 1;
	}
	for($i=0;$i<$num;$i++){
		echo webhost.'reg/'.getCode().'/<br/>';
	}
}
else{
	header("location:".$webinfo["webhost"]);
}
?>

<?php
include("../db.php");
include("../core.php");
function checkStr($str){
	if(preg_match('/^[0-9a-zA-Z]+$/',$str)){return true;}
    return false;
}
if(isset($_POST['domain'])){
	if(strlen(qa($_POST['domain']))<4||strlen(qa($_POST['domain']))>19){
		exit('长度需要在4-20之间');
	}
	elseif(!checkStr(qa($_POST['domain']))){
		exit('只允许使用英文和数字');
	}
	$query = mysql_query("SELECT * FROM wh_domain WHERE domain = '".qa($_POST['domain'])."' LIMIT 1");
	if($query==FALSE){
		exit('× 已被注册');
	}
	if(mysql_num_rows($query)==0){
		exit('√ 可以注册');
	}
	exit('× 已被注册');
}
?>
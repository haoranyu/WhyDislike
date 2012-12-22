<?php
function getTag($tid){
	$query = mysql_query("SELECT * FROM wh_tag WHERE tid = '".$tid."' LIMIT 1");
	$tag = mysql_fetch_array($query);
	return $tag;
}
function getComplain($cid){
	$query = mysql_query("SELECT * FROM wh_complain WHERE cid = '".$cid."' LIMIT 1");
	return mysql_fetch_array($query);
}
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
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
function getTagPagebar($tid,$page){
	$cidArray = array();
	$query = mysql_query("SELECT * FROM wh_complain_tag WHERE tid ='".$tid."'");
	$tempPage = (mysql_num_rows($query)/15);
	if($tempPage<=15){
		$maxPage = $tempPage;
		$minPage = 0;
	}
	else{
		$minPage = $page - 7;
		$maxPage = $page + 7;
		if($minPage<0){
			$maxPage = $maxPage - $minPage;
			$minPage = 0;
		}
		elseif($maxPage>$tempPage){
			$maxPage = $tempPage;
			$minPage = $minPage + ($tempPage-$maxPage);
		}
	}
	$pageString = '';
	if($maxPage<=1){
	return '';
	}
	for($i=$minPage;$i<=$maxPage;$i++){
		$pagenum = $i+1;
		if($pagenum==$page){
			$pageString .= '<span>'.$pagenum.'</span>';
		}
		else{
			$pageString .= '<a href="'.webhost.'tag/'.$tid.'/page/'.$pagenum.'">'.$pagenum.'</a>';
		}
	}
	return '<div class="pagebar">'.$pageString.'</div>';
}
function getDescription($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['description'];
}
function getTag_ComplainArray($tid=0,$page=1){
	$start = ($page-1)*15;
	$cidArray = array();
	$complainArray = array();
	$query = mysql_query("SELECT * FROM wh_complain_tag WHERE tid = '".$tid."' ORDER BY id DESC LIMIT ".$start.",15");
	while($row = mysql_fetch_array($query)){
		if(sizeof($cidArray)<=15){
			array_push($cidArray,$row['cid']);
			$cidArray = array_unique($cidArray);
		}
	}
	foreach($cidArray as $cid){
		array_push($complainArray,getComplain($cid));
	}
	return $complainArray;
}
//ÅÐ¶ÏÊÇ·ñÊÇÎ´µÇÂ¼×´Ì¬
if(!$isLogin){
	header("location:".$webinfo["webhost"].'?next='.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
}
if(isset($_GET['tag'])){
	$tid = intval($_GET['tag']);
}
else{
	header("location:".$webinfo["webhost"].'square/');
}
//pagebar
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
else{
	$page = 1;
}
$tagInfo = getTag($tid);
$tagComplainList = getTag_ComplainArray($tid,$page);
$pagebar = getTagPagebar($tid,$page);
?>
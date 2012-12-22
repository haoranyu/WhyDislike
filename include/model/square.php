<?php
//获得用户列表
function getUser_tagArray($uid){
	$query = mysql_query("SELECT * FROM wh_user_tag WHERE uid = '".$uid."'");
	$array = array();
	while($row = mysql_fetch_array($query)){
		array_push($array,$row['tid']);
	}
	if(sizeof($array)>0){
		return $array;
	}
	else{
		return false;
	}
}
//获得广场页码条
function getSquarePagebar($uid,$page){
	$cidArray = array();
	$tidStr='';
	if(getUser_tagArray($uid)!=false){
		foreach(getUser_tagArray($uid) as $tid){
			if($tidStr==''){
			$tidStr = "tid = '".$tid."' ";
			}
			else{
			$tidStr .= "OR tid = '".$tid."' ";
			}
		}
	}
	$query = mysql_query("SELECT * FROM wh_complain_tag WHERE ".$tidStr);
	if($query!=false){
	$tempPage = (mysql_num_rows($query)/15);
	}
	else{
	$tempPage = 0;
	}
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
			$pageString .= '<a href="'.webhost.'square/page/'.$pagenum.'">'.$pagenum.'</a>';
		}
	}
	return '<div class="pagebar">'.$pageString.'</div>';
}
//获取缺点信息
function getComplain($cid){
	$query = mysql_query("SELECT * FROM wh_complain WHERE cid = '".$cid."' LIMIT 1");
	return mysql_fetch_array($query);
}
//获得用户缺点序列
function getUser_ComplainArray($uid=0,$page=1){
	$start = ($page-1)*15;
	$cidArray = array();
	$complainArray = array();
	$tidStr='';
	if($uid!=0){
		if(getUser_tagArray($uid)!=false){
			foreach(getUser_tagArray($uid) as $tid){
				if($tidStr==''){
				$tidStr = "tid = '".$tid."' ";
				}
				else{
				$tidStr .= "OR tid = '".$tid."' ";
				}
			}
		}
	}
	else{
		foreach(getHottagArray() as $tid){
			if($tidStr==''){
			$tidStr = "tid = '".$tid."' ";
			}
			else{
			$tidStr .= "OR tid = '".$tid."' ";
			}
		}
	}
	$query = mysql_query("SELECT * FROM wh_complain_tag WHERE ".$tidStr."ORDER BY id DESC LIMIT ".$start.",15");
	if($query!=false){
		while($row = mysql_fetch_array($query)){
			if(sizeof($cidArray)<=15){
				array_push($cidArray,$row['cid']);
				$cidArray = array_unique($cidArray);
			}
		}
	}
	foreach($cidArray as $cid){
		array_push($complainArray,getComplain($cid));
	}
	return $complainArray;
}
//获得标签序列字符串
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
//获得热门标签
function getHottagArray(){
	$tag_select = mysql_query("SELECT * FROM wh_tag ORDER BY count DESC LIMIT 9");
	$array = array();
	while($row = mysql_fetch_array($tag_select)){
		array_push($array,$row['tid']);
	}
	return $array;
}
//判断uid是否关注了tid
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
//获得热门tag
function getHottag($uid = 0){
	$array = getHottagArray();
	$tagString='';
	$arrayLength = sizeof($array);
	foreach($array as $tid){
		$query = mysql_query("SELECT * FROM wh_tag WHERE tid = '".$tid."' LIMIT 1");
		$tag = mysql_fetch_array($query);
		$tagString .= '<li><a href="'.webhost.'tag/'.$tag['tid'].'">'.$tag['tag'].' <small> - '.$tag['att'].'人关注</small></a>';
		if(getRelation($uid,$tid)==false){
		$tagString .= '<span wd="attention" tid="'.$tid.'">+关注</span>';
		}else{
		$tagString .= '<span wd="attention" tid="'.$tid.'">已关注</span>';
		}
		$tagString .= '</li>';
	}
	return $tagString;
}
//pagebar
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
else{
	$page = 1;
}
//
$isUserpage = 0;
if($isLogin && (getUser_tagArray($_SESSION['user']['uid'])!=false)){
	$squareList = getUser_ComplainArray($_SESSION['user']['uid'],$page);
	$pagebar = getSquarePagebar($_SESSION['user']['uid'],$page);
	$hotTag = getHottag($_SESSION['user']['uid']);
}
else{
	$squareList = getUser_ComplainArray();
	$pagebar ='';
	$hotTag = getHottag();
}
?>
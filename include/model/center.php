<?php
//Session设置函数
function setSession($name,$info){
	$_SESSION[$name]=$info;
	return 1;
}
//Token获取函数
function getToken($date,$username){
	$token = rand(10,99).$date.md5($username);
	return $token;
}
//登录验证函数
function getLogin($email,$password){
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$email."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$user = mysql_fetch_array($query);
	if($user["verify"] == '0'){
		return false;
	}
	if($user["password"] == $password){
		return $user;
	}
}
function getFav($uid,$cid){
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
function getNotify($uid){
	$query = mysql_query("SELECT * FROM `wh_notify` WHERE `uid` = '".$uid."' AND `read` = 0");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		return mysql_num_rows($query);
	}
}
//获得用户名函数
function getName($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['name'];
}
//获得用户描述函数
function getDescription($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['description'];
}
//获得标签列表字符串函数
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
//获得实时粉丝数函数
function getFanscount($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user['fans'];
}
//获得Dislike列表函数
function getDislike($uid,$page=1,$correct = 0){
	$start = ($page-1)*15;
	$query = mysql_query("SELECT * FROM wh_complain WHERE uid = '".$uid."' AND correct = '".$correct."' ORDER BY cid DESC LIMIT ".$start.",15");
	$array = array();
	while($row = mysql_fetch_array($query,MYSQL_ASSOC)){
		array_push($array,$row);
	}
	return $array;
}
//获得Dislike分页导航栏函数
function getDislikePagebar($uid,$page,$correct = 0){
	$query = mysql_query("SELECT * FROM wh_complain WHERE uid = '".$uid."' AND correct = '".$correct."'");
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
			if(!$correct){
			$pageString .= '<a href="'.webhost.'dislike/page/'.$pagenum.'">'.$pagenum.'</a>';
			}
			else{
			$pageString .= '<a href="'.webhost.'correct/page/'.$pagenum.'">'.$pagenum.'</a>';
			}
		}
	}
	return '<div class="pagebar">'.$pageString.'</div>';
}
//获得聚合Feed流函数
function getCenter($uid,$page=1){
	$start = ($page-1)*15;
	$select_friend = mysql_query("SELECT * FROM wh_friend WHERE uid = '".$uid."' ORDER BY fid DESC LIMIT 50");
	$string = '';
	while($row = mysql_fetch_array($select_friend,MYSQL_ASSOC)){
		if($string == ''){
			$string = "uid = '".$row['fuid']."'";
		}
		else{
			$string .= " OR uid = '".$row['fuid']."'";
		}
	}
	if($string ==''){
	$query = mysql_query("SELECT * FROM wh_complain WHERE (uid = '".$uid."'  AND correct = '0') ORDER BY cid DESC LIMIT ".$start.",15");
	}
	else{
	$query = mysql_query("SELECT * FROM wh_complain WHERE (uid = '".$uid."') OR ((".$string.") AND correct != '0') ORDER BY cid DESC LIMIT ".$start.",15");
	}
	$array = array();
	while($row = mysql_fetch_array($query,MYSQL_ASSOC)){
		if($row['correct']=='1'){
			$row['time']=$row['ctime'];//以修改时间代替时间在数组中用于排序
		}
		array_push($array,$row);
	}
	$arrField = array_map(create_function('$n', 'return $n["time"];'), $array);
	array_multisort($arrField, SORT_DESC, $array);
	return $array;
}
//获得聚合Feed流分页条函数
function getCenterPagebar($uid,$page){
	$query = mysql_query("SELECT * FROM wh_complain WHERE uid = '".$uid."' AND correct = '0'");
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
			$pageString .= '<a href="'.webhost.'center/page/'.$pagenum.'">'.$pagenum.'</a>';
		}
	}
	return '<div class="pagebar">'.$pageString.'</div>';
}
//获得头像函数
function getHead($uid,$size){
	if($size=='s'){
		if(file_exists('img/head/small/'.$uid.'.jpg')&&$uid!=0){
			echo '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/small/'.$uid.'.jpg" /></a>';
		}
		elseif($uid!=0){
			echo '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/small/0.jpg" /></a>';
		}
		else{
			echo '';
		}
	}
	elseif($size=='m'){
		if(file_exists('img/head/medium/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/medium/'.$uid.'.jpg"/>';
		}
		else{
			echo '';
		}
	}
	elseif($size=='l'){
		if(file_exists('img/head/large/'.$uid.'.jpg')){
			echo '<img src="'.webhost.'img/head/large/'.$uid.'.jpg"/>';
		}
		else{
			echo '';
		}
	}
	else{
		echo '';
	}
}
//获得分享头像函数
function getShareHead($uid){
	if(file_exists('img/head/large/'.$uid.'.jpg')){
		echo "'bdPic':'".webhost.'img/head/large/'.$uid.'.jpg'."',";
	}
	else{
		echo "'bdPic':'".webhost.'img/head/large/14.jpg'."',";
	}
}
//获得评论
function getComment($cid){
	$init = mysql_query("SELECT * FROM wh_complain WHERE cid = '".$cid."' LIMIT 1");
	$row_init = mysql_fetch_array($init);
	$query = mysql_query("SELECT * FROM wh_reply WHERE cid = '".$cid."' AND ruid = '".$row_init['uid']."' ORDER BY time ASC LIMIT 2");
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
function checkSameUrl($str1,$str2){
	$arr1 = explode('/',$str1);
	$arr2 = explode('/',$str2);
	if($arr1[0]==$arr2[0]){
		return true;
	}
	else{
		return false;
	}
}
//以下为动作
if((!$isLogin)&&isset($_POST['email'])){
	$email = qa($_POST["email"]);
	$password = qa($_POST["pwd"]);
	$token = getToken(time(),$email);
	if(isset($_POST['save'])&&$_POST['save']=='on'){
		$token = getToken('9999999999',$email);
	}
	if(getLogin($email,$password)){
		setSession("token",$token);
		setSession("email",$email);
		setSession("user",getLogin($email,$password));
		setActTime($_SESSION['user']['uid']);
		$isLogin = 1;
		if(isset($_POST['next'])){
			if(checkSameUrl($_POST['next'],substr(webhost,7))){
				header("location:http://".qa($_POST['next']));
				exit;
			}
			else{
				header("location:".webhost);
				exit;
			}
		}
		else{
			header("location:".$_SERVER['HTTP_REFERER']);//后加的，用于返回之前登录的页面
			exit;
		}
	}
	else{
		header("location:".$webinfo["webhost"].'?note=pwd&uemail='.$email.((isset($_POST['next']))?('&next='.qa($_POST['next'])):''));
		exit;
	}
}
elseif((!$isLogin)){
	header("location:".$webinfo["webhost"].'?next='.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit;
}
//Correct OR Dislike?
if(isset($_GET['correct'])){
	$correct = intval($_GET['correct']);
}
else{
	$correct = 2;
}
//pagebar
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
else{
	$page = 1;
}
//Dislike type
if($correct==0){
	$dislikeList = getDislike($_SESSION['user']['uid'],$page,0);
	$pagebar = getDislikePagebar($_SESSION['user']['uid'],$page,0);
}
elseif($correct==1){
	$dislikeList = getDislike($_SESSION['user']['uid'],$page,1);
	$pagebar = getDislikePagebar($_SESSION['user']['uid'],$page,1);
}
else{
	$dislikeList = getCenter($_SESSION['user']['uid'],$page);
	$pagebar = getCenterPagebar($_SESSION['user']['uid'],$page);
}
?>
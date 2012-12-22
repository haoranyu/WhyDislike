<?php 
include("../core.php");
include("../db.php");
function getFocus($uid){
$aim_list = array();
$query_i2u = mysql_query("SELECT * FROM wh_friend WHERE uid = '".$uid."' ORDER BY fid ASC LIMIT 30");
while($row_i2u = mysql_fetch_array($query_i2u)){
	array_push($aim_list,$row_i2u['fuid']);
}
	return $aim_list;
}function getFans($uid){$aim_list = array();
$query_u2i = mysql_query("SELECT * FROM wh_friend WHERE fuid = '".$uid."' ORDER BY fid  ASC LIMIT 30");
while($row_u2i = mysql_fetch_array($query_u2i)){	
array_push($aim_list,$row_u2i['uid']);}return $aim_list;
}
function getRelated($uid){$aim_list = array();
$query_i2u = mysql_query("SELECT * FROM wh_friend WHERE uid = '".$uid."' ORDER BY fid ASC LIMIT 30");
while($row_i2u = mysql_fetch_array($query_i2u)){	
array_push($aim_list,$row_i2u['fuid']);
}
$query_u2i = mysql_query("SELECT * FROM wh_friend WHERE fuid = '".$uid."' ORDER BY fid  ASC LIMIT 30");
while($row_u2i = mysql_fetch_array($query_u2i)){	
array_push($aim_list,$row_u2i['uid']);
}$aim_list = array_unique($aim_list);
return $aim_list;}function getRecomm($uid){	
$user = getRelated($uid);	
$user_list = array();	
foreach($user as $aim_user){		
foreach(getRelated($aim_user) as $insert_user){			
array_push($user_list,$insert_user);		}	}	
$user_list_final = array_count_values($user_list);	
arsort($user_list_final);	
$uid_list = array();	
$count = 0;	
foreach(array_keys($user_list_final) as $user){		
if($user!=$uid){			
array_push($uid_list,$user);			
$count++;			
if($count==40){break;}		}	}	
return array_slice(array_unique(array_diff(array_merge($uid_list,getFans($uid)), getFocus($uid))),0,8);	}
//获得头像函数
function getHead($uid,$size){	if($size=='s'){		if(file_exists('../../img/head/small/'.$uid.'.jpg')&&$uid!=0){			return '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/small/'.$uid.'.jpg" /></a>';		}		else{			return '<a href="'.webhost.'u/'.$uid.'/" target="_blank"><img src="'.webhost.'img/head/small/0.jpg" /></a>';		}	}	elseif($size=='m'){		if(file_exists('../../img/head/medium/'.$uid.'.jpg')){			return '<img src="'.webhost.'img/head/medium/'.$uid.'.jpg"/>';		}		else{			return '';		}	}	elseif($size=='l'){		if(file_exists('../../img/head/large/'.$uid.'.jpg')){			return '<img src="'.webhost.'img/head/large/'.$uid.'.jpg"/>';		}		else{			return '';		}	}	else{		return '';	}}
//获得用户名函数
function getName($uid){	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");	$user = mysql_fetch_array($query);	return $user['name'];}
//
$uid = qa($_POST['uid']);
$list = getRecomm($uid);?>
<?php if(sizeof($list)>0){
	$info = array();
	foreach($list as $uid){
		$info_son = array(
		'uid' => $uid,
		'head' => getHead($uid,'s'),
		'link' => webhost.'u/'.$uid.'/',
		'name' => subString(getName($uid),0,5),
		);
		array_push($info,$info_son);
	}
	$arr = array(
	0 => true,
	1 => $info,
	2 => sizeof($info),
	);
}else{
	$arr = array(
	0 => false,
	);
}
echo json_encode($arr);
?>
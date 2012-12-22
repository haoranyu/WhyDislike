<?php
include("../../core.php");
include("../../db.php");
header("Content-type:text/html;charset=utf-8");
//加载文件
require_once 'denglu.php';

//初始化接口类Denglu
$api = new Denglu('13077denEFilsc88fX107xGffrSbj3','$1$75851den$wRvj3M7cv8mSGEB6a9I/b1','utf-8');

function setSession($name,$info){
	$_SESSION[$name]=$info;
	return 1;
}
//判断是否已经绑定
function getUser($mid,$muid){
	$query = mysql_query("SELECT * FROM wh_social WHERE mid = '".$mid."' AND muid = '".$muid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$user = mysql_fetch_array($query);
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$user['uid']."' LIMIT 1");
	$row = mysql_fetch_array($query);
	if($row['name']==''){
	return false;
	}
	return $user['uid'];
}

//写入注册
function insertUser($mid,$muid,$url){
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$mid.'_'.$muid."' LIMIT 1");
	$flag = 1;//为了对是否已经写入数据库进行标注
	if($query==FALSE){
		$flag = 0;
	}
	if(mysql_num_rows($query)==0){
		$flag = 0;
	}
	if(0==$flag){
		$query = "INSERT INTO `wh_user`(`email`, `name`, `count`, `correct`, `fans`, `att`, `password`, `description`, `verify`, `alert`, `thide`, `bg`, `vip`) VALUES ('".$mid.'_'.$muid."','','0','0','0','0','','','1','0','0','0','0')";
		mysql_query($query);
	}
	$query = mysql_query("SELECT * FROM wh_user WHERE email = '".$mid.'_'.$muid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	if($url==''){
	$url = '#';
	}
	$query = "INSERT INTO `wh_social`(`mid`, `muid`, `uid`, `url`)  VALUES ('".$mid."','".$muid."','".$user['uid']."','".$url."')";
	mysql_query($query);
	return $user['uid'];
}
//登陆信息
function getLogin($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$user = mysql_fetch_array($query);
	return $user;
}
//登陆信息
function getRenren($url){
	$rrid = substr($url,36);
	$query = mysql_query("SELECT * FROM wh_renren WHERE rrid = '".$rrid."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	$row = mysql_fetch_array($query);
	return $row['uid'];
}
//写入个人信息
function setInfo($user,$username,$imgsrc){
	if($user['name']==''){
		$query = "UPDATE wh_user SET name = '".$username."' WHERE uid = '".$user['uid']."'";
		mysql_query($query);//写update函数
	}
	if(!file_exists('../../../img/head/small/'.$user['uid'].'.jpg')){
		$path_name_s = "../../../img/head/small/";
		$path_name_m= "../../../img/head/medium/";
		$path_name_l= "../../../img/head/large/";
		$file_name = $user['uid'].".jpg"; 
		$targ_w_s = $targ_h_s = 48; //48px
		$targ_w_m = $targ_h_m = 78; //78px
		$targ_w_l = $targ_h_l = 122; //122px
		$jpeg_quality = 100;
		$imginfo= getimagesize($imgsrc);
		$img_r = imagecreatefromjpeg($imgsrc);
		$dst_r_s = ImageCreateTrueColor( $targ_w_s, $targ_h_s );
		$dst_r_m = ImageCreateTrueColor( $targ_w_m, $targ_h_m );
		$dst_r_l = ImageCreateTrueColor( $targ_w_l, $targ_h_l ); //获得截取的宽与高
		imagecopyresampled($dst_r_s,$img_r,0,0,0,0,
		$targ_w_s,$targ_h_s,200,200);
		imagecopyresampled($dst_r_m,$img_r,0,0,0,0,
		$targ_w_m,$targ_h_m,200,200);
		imagecopyresampled($dst_r_l,$img_r,0,0,0,0,
		$targ_w_l,$targ_h_l,200,200); 

		imagejpeg($dst_r_s,$path_name_s.$file_name,$jpeg_quality);
		imagejpeg($dst_r_m,$path_name_m.$file_name,$jpeg_quality);
		imagejpeg($dst_r_l,$path_name_l.$file_name,$jpeg_quality); 
	}
}

function getToken($date,$username){
	$token = rand(10,99).$date.md5($username);
	return $token;
}
//调用接品类相关方法获取媒体用户信息示例
if(!empty($_GET['token'])){
	try{
		$userInfo = $api->getUserInfoByToken($_GET['token']);
		$mid = $userInfo['mediaID'];
		$muid = $userInfo['mediaUserID'];
		$url = ($userInfo['homepage']=='')?$userInfo['url']:$userInfo['homepage'];
		$username = $userInfo['screenName'];
		$mainurl = $userInfo['profileImageUrl'];
		
		
		
		if(getUser($mid,$muid)!=false){//自动登录
			$uid = getUser($mid,$muid);
			$user = getLogin($uid);
			setInfo($user,$username,$mainurl);
			$user = getLogin($uid);
			$email = $user['email'];
			$token = getToken(time(),$email);
			setSession("token",$token);
			setSession("email",$email);
			setSession("user",$user);
			setActTime($_SESSION['user']['uid']);
			try{
				$result = $api->bind( $muid, $user['uid'], '', $email);
			}catch(DengluException $e){
				//处理办法同上
			}
			//exit($_SESSION['token']);
			//exit(getUser($mid,$muid));
			header("location:".webhost);
			exit;
		}
		/*临时策略*/
		
		elseif('7'==$mid&&getRenren($url)!=false){
			$query = "INSERT INTO `wh_social`(`mid`, `muid`, `uid`, `url`) VALUES ('".$mid."','".$muid."','".getRenren($url)."','".$url."')";
			mysql_query($query);
			
			$uid = getUser($mid,$muid);
			$user = getLogin($uid);
			setInfo($user,$username,$mainurl);
			
			$user = getLogin($uid);
			$email = $user['email'];
			$token = getToken(time(),$email);
			setSession("token",$token);
			setSession("email",$email);
			setSession("user",$user);
			setActTime($_SESSION['user']['uid']);
			header("location:".webhost);
			exit;
		}
		
		/*临时策略结束*/
		else{//转入注册页面
			$uid = insertUser($mid,$muid,$url);
			header("location:".webhost.'social/'.$mid.'_'.$muid.'/');
			exit;
		}
	}catch(DengluException $e){//获取异常后的处理办法(请自定义)
		exit('通讯失败，无法连接账户');
		//return false;		
		//echo $e->geterrorCode();  //返回错误编号
		//echo $e->geterrorDescription();  //返回错误信息
	}
}





?>

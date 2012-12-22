<?php
include("../core.php");
include("../db.php");
function getExisttag($tag){
	$query = mysql_query("SELECT * FROM wh_tag WHERE tag = '".$tag."' LIMIT 1");
	if($query==FALSE){
		return false;
	}
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		$row = mysql_fetch_array($query);
		return array($row['tid'],$row['count']);
	}
}
function getRepeat($from,$content){
	$query = mysql_query("SELECT * FROM `wh_complain` WHERE `from`='".$from."' AND `complain`='".$content."' ORDER BY cid DESC LIMIT 1");
	if(mysql_num_rows($query)==0){
		return false;
	}
	else{
		$row = mysql_fetch_array($query);
		if((time() - intval($row['time']))>360){
			return false;
		}
		else{
			return true;
		}
	}
}
function getUserArray($uid){
	$query = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$user = mysql_fetch_array($query);
	return $user;
}
function getCode(){
	$code = md5(uniqid(rand()));
	$query = mysql_query("INSERT INTO wh_invite (code) VALUES ('".$code."')");
	mysql_query($query);
	return $code;
}
function getInvite(){
	$rand = rand(1,100);
	$code = getCode();
	if($rand==1){
		$str = '<br/>恭喜你，你幸运的获得了一个邀请码：'.$code.'，<a href="'.webhost.'reg/'.$code.'/">快去注册</a>！';
	}
	else{
		$str = '<br/>你可以继续为你的朋友提出不足。在这个过程中将送出部分内测邀请码。';
	}
	echo $str;
}
$complain = qa($_POST['complain']);
$uid = qa($_POST['uid']);

if(isset($_SESSION['user']['uid'])){
	if($uid!=$_SESSION['user']['uid']){
		exit;
	}
}
elseif(isset($_POST['cap'])){
	if(md5(strtolower($_POST['cap']))!=$_SESSION['cap']){
	exit('captcha');
	}
}
elseif((!isset($_SESSION['user']['uid']))&&(!isset($_POST['cap']))){
exit('captcha');
}
$from = qa($_POST['from']);
$tags = explode(",",qa($_POST['tags']));
$array = array();
$time = time();
$user = getUserArray($uid);
if(isset($_POST['anonymous'])){
	$anonymous = qa($_POST['anonymous']);
	$Loginflag = 1;	
	if($anonymous!='false'){
		$from = '0';
	}
	else{
		$from = qa($_POST['from']);
	}
}
else{
	$Loginflag = 0;
}
if(getRepeat($from,$complain)!=true){
	foreach($tags as $tag){
		if($tag!=''){
			if(getExisttag($tag)==false){
				$insertQuery = "INSERT INTO `wh_tag`( `tag`, `count`, `att`, `desc`) VALUES ('".$tag."','1','0','')";
				mysql_query($insertQuery);
				$getTag = getExisttag($tag);
				array_push($array,$getTag[0]);
				//echo $insertQuery;
				//exit;
			}
			else{
				$getTag = getExisttag($tag);
				array_push($array,$getTag[0]);
				$getTag[1]++;
				$updateQuery = "UPDATE wh_tag SET count = '".$getTag[1]."' WHERE tid = '".$getTag[0]."' ";
				mysql_query($updateQuery);
			}
		}
	}
	//print_r($array);
	//exit;
	//$tagString = implode(',',$array);
	$insertCPL = "INSERT INTO `wh_complain`(`uid`,`complain`, `from`, `correct`, `reply`, `language`, `time`) VALUES ('".$uid."','".$complain."','".$from."','0','','zh','".$time."')";
	mysql_query($insertCPL);
	$queryCPL = mysql_query("SELECT * FROM wh_complain WHERE uid = '".$uid."' AND time = '".$time."' LIMIT 1");
	$rowc = mysql_fetch_array($queryCPL);
	$cid = $rowc['cid'];
	foreach($array as $tid){
		$queryTAG="INSERT INTO `wh_complain_tag`(`cid`,`uid`,`tid`) VALUES('".$cid."','".$uid."','".$tid."')";
		mysql_query($queryTAG);
	}
	$querySEL = mysql_query("SELECT * FROM wh_user WHERE uid = '".$uid."' LIMIT 1");
	$row = mysql_fetch_array($querySEL);
	$row['count']++;
	$updateCPL = "UPDATE wh_user SET count = '".$row['count']."' WHERE uid = '".$uid."' ";
	mysql_query($updateCPL);
	//以下获得发出者名称
	if($Loginflag){
	$queryFR = mysql_query("SELECT * FROM wh_user WHERE uid = '".$from."' LIMIT 1");
	$row2 = mysql_fetch_array($queryFR);
	$fromName = $row2['name'];
	}
	else{
	$fromName = '某人';
	}
	$email = $row['email'];
	$title = $fromName."指出了一个你的不足";
	$content = '<body style="border-width:0;margin:12px;"lang="en"style="background-color:#fff; color: #222"><div style="padding:14px; margin-bottom:4px; background-color:#072D3A; -moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px"><img src="'.webhost.'img/logo_sml.jpg"style="display:block;border: 0;" height="30px" width="129px"/></div><div style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif; font-size:13px; margin: 14px; position:relative"><h2 style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif;margin:0 0 16px; font-size:18px; font-weight:normal">'.$fromName.'指出了一个你的不足</h2><p>亲爱的'.$row['name'].'：</p><p>'.$fromName.'指出了一个你的不足。你可以到 <a href="'.webhost.'center/" target="_blank">你的首页</a> 去改正。</p><p>'.$fromName.'："'.$complain.'"</p><p>WhyDislike运营团队</p></div></body>';
	if($user['alert']==1){
		postmail($email,$email,$title,$content);
	}
}
?>
<?php if($Loginflag){?>
	感谢您为他指出了不足。 <a href="">继续指出不足</a><br/><br/><br/>您可以邀请您的好友给您指出不足。邀请好友<br/>去 <a href="<?php echo $webinfo["webhost"]?>">首页</a> 看看大家给我提出的不足。
<?php }else{?>
	感谢您为他指出了不足。<br/><br/><?php getInvite()?><br/><br/>已经注册？ <a href="<?php echo $webinfo["webhost"]?>" target="_blank">登录</a>
<?php }?>
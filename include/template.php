<?php
if(isset($_GET['model'])){
	$model = $_GET['model'];
}
else{
	$model = "login";
}
switch($model){
	case "login":include("template/login.php");break;
	case "logout":include("template/logout.php");break;
	case "center":include("template/center.php");break;
	case "setting":include("template/setting.php");break;
	case "user":include("template/user.php");break;
	case "friend":include("template/friend.php");break;
	case "square":include("template/square.php");break;
	case "tag":include("template/tag.php");break;
	case "reg":include("template/reg.php");break;
	case "fav":include("template/fav.php");break;
	case "dislike":include("template/dislike.php");break;
	case "social":include("template/social.php");break;
}
?>
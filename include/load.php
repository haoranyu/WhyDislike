<?php
include("core.php");
if(isset($_GET['model'])){
	$model = $_GET['model'];
}
else{
	$model = "login";
}
switch($model){
	case "login":include("model/login.php");break;
	case "logout":include("model/logout.php");break;
	case "center":include("model/center.php");break;
	case "setting":include("model/setting.php");break;
	case "user":include("model/user.php");break;
	case "friend":include("model/friend.php");break;
	case "square":include("model/square.php");break;
	case "tag":include("model/tag.php");break;
	case "reg":include("model/reg.php");break;
	case "fav":include("model/fav.php");break;
	case "dislike":include("model/dislike.php");break;
	case "social":include("model/social.php");break;
}
?>
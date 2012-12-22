<?php
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
include("../../core.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$path_name_s = "../../../img/head/small/";
	$path_name_m= "../../../img/head/medium/";
	$path_name_l= "../../../img/head/large/";
	$file_name = $_SESSION['user']['uid'].".jpg"; //可以根据用户名变化
	
	$targ_w_s = $targ_h_s = 48; //48px
	$targ_w_m = $targ_h_m = 78; //78px
	$targ_w_l = $targ_h_l = 200; //200px

	$jpeg_quality = 100;
	
	$imginfo= getimagesize($_POST['src']);
	if('image/jpeg'==$imginfo['mime']){
		$img_r = imagecreatefromjpeg($_POST['src']);
	}
	elseif('image/gif'==$imginfo['mime']){
		$img_r = imagecreatefromgif($_POST['src']);
	}
	else{
		$img_r = imagecreatefrompng($_POST['src']);
	}
	
	$dst_r_s = ImageCreateTrueColor( $targ_w_s, $targ_h_s );
	$dst_r_m = ImageCreateTrueColor( $targ_w_m, $targ_h_m );
	$dst_r_l = ImageCreateTrueColor( $targ_w_l, $targ_h_l ); //获得截取的宽与高

	$test1 = $imginfo[0]/300;
	$test2 = $imginfo[1]/230;
	
	if($test1>$test2){
		$finalX = $_POST['x']*($test1);
		$finalY = $_POST['y']*($test1);
		$finalW = $_POST['w']*($test1);
		$finalH = $_POST['h'] *($test1);
	}
	else{
		$finalX = $_POST['x']*($test2);
		$finalY = $_POST['y']*($test2);
		$finalW = $_POST['w']*($test2);
		$finalH = $_POST['h']*($test2);
	}
	imagecopyresampled($dst_r_s,$img_r,0,0,$finalX,$finalY,
	$targ_w_s,$targ_h_s,$finalW,$finalH);
	imagecopyresampled($dst_r_m,$img_r,0,0,$finalX,$finalY,
	$targ_w_m,$targ_h_m,$finalW,$finalH);
	imagecopyresampled($dst_r_l,$img_r,0,0,$finalX,$finalY,
	$targ_w_l,$targ_h_l,$finalW,$finalH); 

	imagejpeg($dst_r_s,$path_name_s.$file_name,$jpeg_quality);
	imagejpeg($dst_r_m,$path_name_m.$file_name,$jpeg_quality);
	imagejpeg($dst_r_l,$path_name_l.$file_name,$jpeg_quality); //按设定的路径生成头像文件
	@unlink ($_POST['src']);
}
else{
header("location:".$webinfo["webhost"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>头像上传成功</title>
<?php css('head')?>
</head>
<body>
<div class="success">更改成功！<a href="index.php">返回重设</a></div>
<div class="views">
<ul>
<li><div><img width="122px" height="122px" src="<?php p($path_name_l.$file_name.'?'.time())?>" alt="large" /></div><span>大头像</span></li>
<li><div><img src="<?php p($path_name_m.$file_name.'?'.time())?>" alt="medium" /></div><span>中头像</span></li>
<li><div><img src="<?php p($path_name_s.$file_name.'?'.time())?>" alt="small" /></div><span>小头像</span></li>
</ul>
</div>
</body>
</html>

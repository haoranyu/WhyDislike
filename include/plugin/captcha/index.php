<?php
session_start();
function getCAP($text , $im_x = 120 , $im_y = 30) {
	$im = imagecreatetruecolor($im_x,$im_y);
	//base color
	$base_color=mt_rand(220,255);
	//background color RGB
	$back_color1=$base_color-mt_rand(0,10);
	$back_color2=$base_color-mt_rand(0,10);
	$back_color3=$base_color-mt_rand(0,10);
	//font color RGB
	$font_color1=$base_color-mt_rand(150,160);
	$font_color2=$base_color-mt_rand(150,160);
	$font_color3=$base_color-mt_rand(150,160);
	
	//set colors
	$font_color = ImageColorAllocate($im, $font_color1,$font_color2,$font_color3);
	$back_color = ImageColorAllocate($im,$back_color1,$back_color2,$back_color3);
	imagefill($im, 16, 13, $back_color);

	//radom fonts
	$font = mt_rand(1,6).'.ttf';
	
	//draw words
	for ($i=0;$i<strlen($text);$i++){
	   $base_color =substr($text,$i,1);
	   $array = array(-1,1);
	   $p = array_rand($array);
	   $an = $array[$p]*mt_rand(-15,15);//½Ç¶È
	   $size = 18;
	   imagettftext($im,$size,$an,10+$i*$size,25,$font_color,$font,$base_color);
	}
	$distortion_im = imagecreatetruecolor ($im_x, $im_y);
	imagefill($distortion_im, 16, 13, $back_color);
	for ( $i=0; $i<$im_x; $i++){
		for ( $j=0; $j<$im_y; $j++){
			$rgb = imagecolorat($im, $i , $j);
			if( (int)($i+10+sin($j/$im_y*2*M_PI)*3) <= imagesx($distortion_im) && (int)($i+10+sin($j/$im_y*2*M_PI)*3) >=0 ) {
				imagesetpixel ($distortion_im, (int)($i+10+sin($j/$im_y*2*M_PI-M_PI*0.5)*6) , $j , $rgb);
			}
		}
	}
	
	//add noise points
	$count = 50;
	for($i=0; $i<$count; $i++){
	   $randcolor = ImageColorallocate($distortion_im,$back_color1,$back_color2,$back_color3);
	   imagesetpixel($distortion_im, mt_rand()%$im_x , mt_rand()%$im_y , $randcolor);
	}


	//add noise lines and curves
	$line_count=2;
	for($i=0; $i < $line_count; $i++) {
	   $linecolor = imagecolorallocate($distortion_im, $font_color1, $font_color2, $font_color3);
	   $arccolor = imagecolorallocate($distortion_im, $back_color1,$back_color2, $back_color3);
	   $lefty = mt_rand(1, $im_x-1);
	   $righty = mt_rand(1, $im_y-1);
	   imageline($distortion_im, 0, $lefty, imagesx($distortion_im), $righty, $linecolor);
	   imagearc($distortion_im, 0, $lefty, imagesx($distortion_im), $righty,35, 190, $arccolor);
	}

	//Set header
	Header("Content-type: image/PNG");

	//Output the PNG image
	ImagePNG($distortion_im);

	//Destroy the image and release memory;
	ImageDestroy($distortion_im);
	ImageDestroy($im);
}
function make_rand($length="32"){
    $str="AbcCDeEFGhHiJKLMNPQrRsSTuwWxXyY34567";
    $result="";
    for($i=0;$i<$length;$i++){
        $num[$i]=rand(0,strlen($str)-1);
        $result.=$str[$num[$i]];
    }
    return $result;
}

//get final result
$string = make_rand(4);
$_SESSION['cap']=md5(strtolower($string));
getCAP($string);
?>
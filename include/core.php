<?php
date_default_timezone_set("Asia/Shanghai"); 
session_cache_expire(999999999);
session_start();
define("webhost",'http://'.$_SERVER ['HTTP_HOST'].'/');
$webinfo = array(
	"webname" => "WhyDislike",
	"websubname" => "做更好的自己",
	"webhost" => webhost,
	"description" => "WhyDislike帮你发现每一点的不足，陪你一步步的改进自己的不足",
);
//输出函数
function p($str){
	echo $str;
}
//调入css函数
function css($str,$v=0){
	echo '<link href="'.webhost.'css/'.$str.'.css?v='.$v.'" rel="stylesheet" type="text/css"/>';
}
//调入js函数
function js($str){
	echo '<script type="text/javascript" src="'.webhost.'js/'.$str.'.js"></script>';
}
//输入输出安全过滤函数，反SQL注入
function qa($html){
	// $html 应包含一个 HTML 文档。
    // 本例将去掉 HTML 标记，javascript 代码
    // 和空白字符。还会将一些通用的
    // HTML 实体转换成相应的文本。
    $search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                    "'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 标记
                    "'([\r\n])[\s]+'",                 // 去掉空白字符
                    "'&(quot|#34);'i",                 // 替换 HTML 实体
                    "'&(amp|#38);'i",
                    "'&(lt|#60);'i",
                    "'&(gt|#62);'i",
                    "'&(nbsp|#160);'i",
                    "'&(iexcl|#161);'i",
                    "'&(cent|#162);'i",
                    "'&(pound|#163);'i",
                    "'&(copy|#169);'i",
                    "'&#(\d+);'e");                    // 作为 PHP 代码运行
    $replace = array ("",
                     "",
                     "\\1",
                     "\"",
                     "&",
                     "<",
                     ">",
                     " ",
                     chr(161),
                     chr(162),
                     chr(163),
                     chr(169),
                     "chr(\\1)");
    $text = preg_replace ($search, $replace, $html);
    $text = trim($text);
    return str_replace('`','&#96;',htmlspecialchars($text));
}
//邮件投递函数
function postmail($to,$name,$subject = "",$body = ""){
    //error_reporting(E_ALL);
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//设定时区东八区
    require_once('plugin/mail/class.phpmailer.php');
    include("plugin/mail/class.smtp.php"); 
    $mail             = new PHPMailer(); //new一个PHPMailer对象出来
    $body             = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
    $mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
                                           // 1 = errors and messages
                                           // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = "";                 // 安全协议
    $mail->Host       = "smtp.ym.163.com";      // SMTP 服务器
    $mail->Port       = 25;                   // SMTP服务器的端口号
    $mail->Username   = "admin@whydislike.com";  // SMTP服务器用户名
    $mail->Password   = "yhrnew2010";            // SMTP服务器密码
    $mail->SetFrom('admin@whydislike.com', 'WhyDislike');
    $mail->AddReplyTo("admin@whydislike.com","WhyDislike");
    $mail->Subject    = $subject;
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From Whydislike.com"; // optional, comment out and test
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, $name);
    //$mail->AddAttachment("images/phpmailer.gif");      // attachment 
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
	return true;
}
//消息系统函数
function postnote($uid = 0,$cid = 0,$content = ""){
	$time = time();
	$query="INSERT INTO `wh_notify`(`uid`, `cid`, `content`, `read`, `time`) VALUES ('".$uid."','".$cid."','".$content."','0','".$time."')";
	mysql_query($query);
	//选择性发送邮件（现在无法正常运行，待改好）
	$query = mysql_query("SELECT * FROM `wh_user_time` WHERE `uid` = '".$uid."' LIMIT 1");
	$flag = 1;
	if($query==FALSE){
		$flag = 0;
	}
	if(mysql_num_rows($query)==0){
		$flag = 0;
	}
	if($flag == 0){
		$time_delta = 4000;
	}
	else{
		$row = mysql_fetch_array($query);
		$time_delta = time() - intval($row['time']);
	}
	if($time_delta >= 3600){
		$content = strip_tags($content);
		$query2 = mysql_query("SELECT * FROM `wh_user` WHERE `uid` = '".$uid."' LIMIT 1");
		$row2 = mysql_fetch_array($query2);
		$email = $row2['email'];
		$title = '有人回复了您的消息';
		$content = '<body style="border-width:0;margin:12px;"lang="en"style="background-color:#fff; color: #222"><div style="padding:14px; margin-bottom:4px; background-color:#072D3A; -moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px"><img src="'.webhost.'img/logo_sml.jpg"style="display:block;border: 0;" height="30px" width="129px"/></div><div style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif; font-size:13px; margin: 14px; position:relative"><h2 style="font-family: \'Helvetica Neue\', Arial, Helvetica, sans-serif;margin:0 0 16px; font-size:18px; font-weight:normal">有人回复了您的消息</h2><p>亲爱的'.$row2['name'].'：</p><p>'.$content.'。你可以到 <a href="'.webhost.'dislike/'.$cid.'/" target="_blank">相关页面</a> 去查看。</p><p>WhyDislike运营团队</p></div></body>';
		postmail($email,$email,$title,$content);
	}
}
function modifynote($uid = 0, $cid = 0){
	$query_init = mysql_query("SELECT * FROM `wh_notify` WHERE `uid` = '".$uid."' AND `cid` = '".$cid."' AND `read` ='0' LIMIT 1");
	if($query_init==FALSE){
		return false;
	}
	if(mysql_num_rows($query_init)==0){
		return false;
	}
	else{
	//cid说明：0为新出现的缺点、-1为新出现的粉丝
	$query = "UPDATE `wh_notify` SET `read` = '1' WHERE `uid` = '".$uid."' AND `cid` = '".$cid."' AND `read` ='0'";
	mysql_query($query);
	}
}
//定时间的加密解密函数
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600) {
	$ckey_length = 4;
	// 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥
	$key = md5 ( $key ? $key : 'key' ); //这里可以填写默认key值
	$keya = md5 ( substr ( $key, 0, 16 ) );
	$keyb = md5 ( substr ( $key, 16, 16 ) );
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';
	
	$cryptkey = $keya . md5 ( $keya . $keyc );
	$key_length = strlen ( $cryptkey );
	
	$string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
	$string_length = strlen ( $string );
	
	$result = '';
	$box = range ( 0, 255 );
	
	$rndkey = array ();
	for($i = 0; $i <= 255; $i ++) {
		$rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
	}
	
	for($j = $i = 0; $i < 256; $i ++) {
		$j = ($j + $box [$i] + $rndkey [$i]) % 256;
		$tmp = $box [$i];
		$box [$i] = $box [$j];
		$box [$j] = $tmp;
	}
	
	for($a = $j = $i = 0; $i < $string_length; $i ++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box [$a]) % 256;
		$tmp = $box [$a];
		$box [$a] = $box [$j];
		$box [$j] = $tmp;
		$result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
	}
	
	if ($operation == 'DECODE') {
		if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {
			return substr ( $result, 26 );
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
	}
}

//可用于中文的字符串截取函数
function subString($str,$start=0, $cutLength, $etc = '...') {

    $result = '';
    $i = 0;
    $n = 0.0;
    $strLength = strlen($str); //字符串的字节数

    while ($n < $cutLength && $i < $strLength) {

        $tempStr = substr($str, $i, 1);
        $ascnum = ord($tempStr); //得到字符串中第$i位字符的ASCII码

        if ($ascnum >= 252) {

            //如果ASCII位高于252
            $result = $result . substr($str, $i, 6); //根据UTF-8编码规范，将6个连续的字符计为单个字符
            $i = $i + 6; //实际Byte计为6
            $n ++; //字串长度计1

        } elseif ($ascnum >= 248) {

            //如果ASCII位高于248
            $result = $result . substr($str, $i, 5); //根据UTF-8编码规范，将5个连续的字符计为单个字符
            $i = $i + 5; //实际Byte计为5
            $n ++; //字串长度计1

        } elseif ($ascnum >= 240) {

            //如果ASCII位高于240
            $result = $result . substr($str, $i, 4); //根据UTF-8编码规范，将4个连续的字符计为单个字符
            $i = $i + 4; //实际Byte计为4
            $n ++; //字串长度计1

        } elseif ($ascnum >= 224) {

            //如果ASCII位高于224
            $result = $result . substr($str, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = $i + 3 ; //实际Byte计为3
            $n ++; //字串长度计1

        } elseif ($ascnum >= 192) {

            //如果ASCII位高于192
            $result = $result . substr($str, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = $i + 2; //实际Byte计为2
            $n ++; //字串长度计1

        } elseif ($ascnum >= 65 && $ascnum <= 90 && $ascnum != 73) {

            //如果是大写字母 I除外
            $result = $result . substr($str, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n ++; //但考虑整体美观，大写字母计成一个高位字符

        } elseif (!(array_search($ascnum, array(37, 38, 64, 109 ,119)) === FALSE)) {

            //%,&,@,m,w 字符按１个字符宽
            $result = $result . substr($str, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n ++; //但考虑整体美观，这些字条计成一个高位字符

        } else {

            //其他情况下，包括小写字母和半角标点符号
            $result = $result . substr($str, $i, 1);
            $i = $i + 1; //实际的Byte数计1个
            $n = $n + 0.5; //其余的小写字母和半角标点等于半个高位字符宽...
        }
    }
    if ($i < $strLength) {
        $result = $result . $etc; //超过长度时在尾处加上省略号
    }

    return $result;
}
function setActTime($uid){
	$query = mysql_query("SELECT * FROM wh_user_time WHERE uid = '".$uid."' LIMIT 1");
	$flag = 1;
	$time = time();
	if($query==FALSE){
		$flag = 0;
	}
	if(mysql_num_rows($query)==0){
		$flag = 0;
	}
	if($flag == 1){
		$query = "UPDATE wh_user_time SET time = '".$time."' WHERE uid = '".$uid."' ";
		mysql_query($query);
	}
	else{
		$query = "INSERT INTO `wh_user_time`(`uid`, `time`) VALUES ('".$uid."','".$time."')";
		mysql_query($query);
	}
}
//往下都是动作了
if(isset($_SESSION['token'])){
	$isLogin = 1;
}
else{
	$isLogin = 0;
}
if($isLogin){
	if(time()-intval(substr($_SESSION['token'],2,10))>86400){//过期了
		unset($_SESSION['token']);
		header("location:".$webinfo["webhost"]);
	}
	else{//没过期
		$email = $_SESSION['email'];
	}
}
?>
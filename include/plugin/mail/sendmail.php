<?php
function sendmail($to,$name,$subject = "",$body = ""){
    //error_reporting(E_ALL);
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//设定时区东八区
    require_once('class.phpmailer.php');
    include("class.smtp.php"); 
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

$to = $GET['to'];
$name = $GET['name'];
$subject = $GET['sub'];
$body = htmlspecialchars_decode($GET['body']);
sendmail($to,$name,$subject,$body);
?>
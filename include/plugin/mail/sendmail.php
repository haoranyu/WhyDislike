<?php
function sendmail($to,$name,$subject = "",$body = ""){
    //error_reporting(E_ALL);
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//�趨ʱ��������
    require_once('class.phpmailer.php');
    include("class.smtp.php"); 
    $mail             = new PHPMailer(); //newһ��PHPMailer�������
    $body             = eregi_replace("[\]",'',$body); //���ʼ����ݽ��б�Ҫ�Ĺ���
    $mail->CharSet ="UTF-8";//�趨�ʼ����룬Ĭ��ISO-8859-1����������Ĵ���������ã���������
    $mail->IsSMTP(); // �趨ʹ��SMTP����
    $mail->SMTPDebug  = 1;                     // ����SMTP���Թ���
                                           // 1 = errors and messages
                                           // 2 = messages only
    $mail->SMTPAuth   = true;                  // ���� SMTP ��֤����
    $mail->SMTPSecure = "";                 // ��ȫЭ��
    $mail->Host       = "smtp.ym.163.com";      // SMTP ������
    $mail->Port       = 25;                   // SMTP�������Ķ˿ں�
    $mail->Username   = "admin@whydislike.com";  // SMTP�������û���
    $mail->Password   = "yhrnew2010";            // SMTP����������
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
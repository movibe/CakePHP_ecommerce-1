<?php
//ini_set('include_path', ini_get('include_path'). ':/srv/externals/php/phpmailer/:');

require("phpmailer.inc.php");

$mail = new phpmailer;

//$mail->IsSMTP(); // set mailer to use SMTP
$mail->From = "from@email.com";
$mail->FromName = "Mailer";
//$mail->Host = "smtp1.site.com;smtp2.site.com";  // specify main and backup server
$mail->AddAddress("arun88m@gmail.com", "Josh Adams");
$mail->AddAddress("ellen@site.com");   // name is optional
$mail->AddReplyTo("info@site.com", "Information");
$mail->WordWrap = 50;    // set word wrap
//$mail->AddAttachment("c:\\temp\\js-bak.sql");  // add attachments
//$mail->AddAttachment("c:/temp/11-10-00.zip");

$mail->IsHTML(true);    // set email format to HTML
$mail->Subject = "Here is the subject";
$mail->Body = "This is the message body";
$mail->Send(); // send message
?>
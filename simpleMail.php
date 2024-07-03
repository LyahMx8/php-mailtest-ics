<?php
$to = "yimsanabria@gmail.com, lyah.smo@gmail.com";
$subject = "HTML email";

$message = "testmail";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <lyah.smo@gmail.com>' . "\r\n";

try {
    mail($to,$subject,$message,$headers);
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error:";
}
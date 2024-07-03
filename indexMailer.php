<?php 

require_once('PHPMailer.php');

$mail = new PHPMailer\PHPMailer\PHPMailer();

$from_name		= "Lyah Rally";
$from_address	= "lyah.smo@gmail.com";
$to_name		= "Camilo Rally";
$to_address		= "yimsanabria@gmail.com";
$to_name2		= "Camilo Rally";
$to_address2	= "yimsanabria@gmail.com";
$start		    = "2024-07-06 09:00";
$end		    = "2024-07-06 21:00";
$subject		= "Test meeting";
$description	= "Hey there!\n I'm programming this test meeting to talk about the new hire";
$location		= "La Picota, Bogotá";

//Recipients
$mail->setFrom($from_address, $from_name);
$mail->addAddress('tyler@benefitscompliancesolutions.com', 'Tyler Borders'); //Name is optional
$mail->addAddress('camilo.casadiego@trotalo.com', 'Camilo Casadiego');

$data = "BEGIN:VCALENDAR\n".
    "VERSION:2.0\n".
    "METHOD:PUBLISH\n".
    "BEGIN:VEVENT\n".
    "DTSTART:".date("Ymd\THis\Z",strtotime($start)).
    "\nDTEND:".date("Ymd\THis\Z",strtotime($end)).
    "\nLOCATION:".$location.
    "\nTRANSP: OPAQUE".
    "\nSEQUENCE:0".
    "\nUID:".
    "\nDTSTAMP:".date("Ymd\THis\Z").
    "\nSUMMARY:".$from_name.
    "\nORGANIZER;CN=".$to_name2.":mailto:".$to_address2.
    "\nDESCRIPTION:".$description.
    "\nPRIORITY:1\n".
    "CLASS:PUBLIC\n".
    "BEGIN:VALARM\n".
    "TRIGGER:-PT10080M".
    "\nACTION:DISPLAY".
    "\nDESCRIPTION:Reminder".
    "\nEND:VALARM".
    "\nEND:VEVENT".
    "\nEND:VCALENDAR\n";

//Attachments
$mail->AddStringAttachment($data,'ical.ics','base64','text/calendar');

//Content
$mail->MsgHTML($description);                                  //Set email format to HTML
$mail->Subject = $subject;
$mail->Body    = $description;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

try {
    $mail->send();
    echo 'Mensaj has bin sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
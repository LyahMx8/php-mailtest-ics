<?php

require_once('PHPMailer.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CalendarInvitation
{
    private $summary;
    private $location;
    private $description;
    private $colorId;
    private $start;
    private $end;
    private $attendees;
    private $reminders;
    private $creator;
    private $organizer;

    public function __construct($data)
    {
        $this->summary = $data['summary'];
        $this->location = $data['location'];
        $this->description = $data['description'];
        $this->colorId = $data['colorId'];
        $this->start = $data['start'];
        $this->end = $data['end'];
        $this->attendees = $data['attendees'];
        $this->reminders = $data['reminders'];
        $this->creator = $data['creator'];
        $this->organizer = $data['organizer'];
    }

    private function createICS()
    {
        $attendees = "";
        foreach ($this->attendees as $attendee) {
            $attendees .= "ATTENDEE;CN={$attendee['email']}:mailto:{$attendee['email']}\r\n";
        }

        return "BEGIN:VCALENDAR\r\n" .
               "VERSION:2.0\r\n" .
               "PRODID:-//Your Company//NONSGML Your Product//EN\r\n" .
               "METHOD:REQUEST\r\n" .
               "BEGIN:VEVENT\r\n" .
               "UID:" . uniqid() . "\r\n" .
               "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n" .
               "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($this->start['dateTime'])) . "\r\n" .
               "DTEND:" . gmdate('Ymd\THis\Z', strtotime($this->end['dateTime'])) . "\r\n" .
               "SUMMARY:{$this->summary}\r\n" .
               "DESCRIPTION:{$this->description}\r\n" .
               "LOCATION:{$this->location}\r\n" .
               "ORGANIZER;CN={$this->organizer['email']}:mailto:{$this->organizer['email']}\r\n" .
               $attendees .
               "BEGIN:VALARM\r\n" .
               "TRIGGER:-PT10M\r\n" .
               "ACTION:DISPLAY\r\n" .
               "DESCRIPTION:Reminder\r\n" .
               "END:VALARM\r\n" .
               "END:VEVENT\r\n" .
               "END:VCALENDAR\r\n";
    }

    public function sendInvitation()
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'email';
            $mail->Password = 'password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatarios
            foreach ($this->attendees as $attendee) {
                $mail->addAddress($attendee['email']);
            }
            $mail->setFrom($this->creator['email'], 'Organizer Name');
            $mail->addReplyTo($this->creator['email'], 'Organizer Name');

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Calendar Invitation: ' . $this->summary;
            $mail->Body = 'You have been invited to an event. Please see the attached calendar invitation.';
            $mail->AltBody = 'You have been invited to an event. Please see the attached calendar invitation.';
            $mail->addStringAttachment($this->createICS(), 'invitation.ics', 'base64', 'text/calendar');

            $mail->send();
            echo 'Invitation has been sent';
        } catch (Exception $e) {
            echo "Invitation could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Datos de ejemplo
$data = [
    "summary" => "1:1 Business Craft",
    "location" => "Google Meet: Please follow the google meet url",
    "description" => "Business teammates Q2Q@/&$$/()(&ñ",
    "colorId" => 2,
    "start" => ["dateTime" => "2024-06-29T11:00:00+05:30", "timeZone" => "America/Bogota"],
    "end" => ["dateTime" => "2024-06-30T12:00:00+05:30", "timeZone" => "America/Bogota"],
    "attendees" => [
        ["email" => "attendee1@gmail.com"],
        ["email" => "attendee2@gmail.com"],
        ["email" => "organizer@gmail.com", "organizer" => true]
    ],
    "reminders" => [
        "useDefault" => false,
        "overrides" => [
            ["method" => "email", "minutes" => 10],
            ["method" => "popup", "minutes" => 10]
        ]
    ],
    "creator" => [
        "email" => "organizer@gmail.com",
        "self" => true
    ],
    "organizer" => [
        "email" => "organizer@gmail.com",
        "self" => true
    ]
];

$invitation = new CalendarInvitation($data);
$invitation->sendInvitation();
?>

<?php
echo "entra";

class ICS {
	public $data;
	public $name;
	
	function setICS($start, $end, $name, $description, $location) {
		$this->name = $name;
		$this->data = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VEVENT\nDTSTART:".date("Ymd\THis\Z",strtotime($start))."\nDTEND:".date("Ymd\THis\Z",strtotime($end))."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT10080M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\nEND:VCALENDAR\n";
	}
	function save() {
		file_put_contents($this->name.".ics",$this->data);
	}
	function show() {
		header("Content-type:text/calendar");
		header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
		Header('Content-Length: '.strlen($this->data));
		
		echo $this->data;

		$mailsent = mail($to_address, $subject, $message, $headers);
	 
		return ($mailsent)?(true):(false);
	}
}

$from_name		= "Lyah Rally";
$from_address	= "lyah.smo@gmail.com";
$to_name		= "Camilo Rally";
$to_address		= "yimsanabria@gmail.com";
$startTime		= "2024-07-06 09:00";
$endTime 		= "2024-07-06 21:00";
$subject		= "1:1 Camilo - Lyah";
$description	= " Hola tu  te recodamos que tienes una reunion ñññ con tal en x <br><center><img src='https://blogs.iadb.org/seguridad-ciudadana/wp-content/uploads/sites/27/2016/05/alcatraz.jpg' width='350px' height='350px' ></center>" ;
$location		= "La Picota, Bogotá";

$event = new ICS();
$event->setICS($startTime, $endTime, $subject, $description, $location);
echo $event->show();

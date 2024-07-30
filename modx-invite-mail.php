<?php

use MODX\Revolution\Rest\modRestController;
use MODX\Revolution\Rest\modRestServiceRequest;
use xPDO\Om\xPDOObject;
require_once "BaseController.php";

class BcsCalendarInvite extends BaseController {

    /** @var string $classKey The xPDO class to use */
    public $classKey = 'Bcs\Model\bcsAnswers';

    /** @var string $classAlias The alias of the class when used in the getList method */
    public $classAlias  = 'bcsAnswers';

    /** @var string $defaultSortField The default field to sort by in the getList method */
    public $defaultSortField = 'id';
    /** @var string $defaultSortDirection The default direction to sort in the getList method */
    public $defaultSortDirection = 'ASC';

    public $primaryKeyField = 'id';

    public $isDevelopment = true;

    public function __construct(modX $modx,modRestServiceRequest $request,array $config = array()){
      parent::__construct($modx, $request, $config);
    }

    public function get()
    {
        
        $from_name		= "Lyah Motta";
        $from_address	= "lyah.smo@gmail.com";
        $to_name		= "Camilo";
        $to_address		= "yimsanabria@gmail.com";
        $startTime		= "2024-07-06 09:00";
        $endTime 		= "2024-07-06 21:00";
        $subject		= "1:1 Camilo - Lyah";
        $description	= " Hola tu  te recodamos que tienes una reunion ñññ con tal en x <br>" ;
        $location		= "La Candelaria, Bogotá";

        $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->modx->mail->set(modMail::MAIL_BODY, $description);
        $this->modx->mail->set(modMail::MAIL_SENDER, $from_address);
        $this->modx->mail->set(modMail::MAIL_SUBJECT, $subject);
        $this->modx->mail->address('to', $to_address, $to_name);
        $this->modx->mail->setHTML(true);
        if (!$this->modx->mail->send()) {

            echo "<b>SMTP errors:</b><br />";
            $this->modx->mail->mailer->SMTPDebug = 2;
            $this->modx->mail->send();
            
            echo '<br /><br /><b>' . "Mailer ErrorInfo:</b><br />";
            die($this->modx->mail->mailer->ErrorInfo);

            die('<b>' . $this->modx->lexicon('mail-failure') . '</b>');

        }
    $this->modx->mail->reset();

    }

<<<<<<< HEAD
}
=======
}
>>>>>>> 0281844631d4fe9865a8fab0191523fac9eabb0d

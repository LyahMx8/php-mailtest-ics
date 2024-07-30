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

    public function __construct(modX $modx,modRestServiceRequest $request,array $config = array()) {
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
        $description	= " Hola tu  te recodamos que tienes una reunion ñññ con tal en x <br><center><img src='https://blogs.iadb.org/seguridad-ciudadana/wp-content/uploads/sites/27/2016/05/alcatraz.jpg' width='350px' height='350px' ></center>" ;
        $location		= "La Candelaria, Bogotá";

        $props = array(
            'debug' => '0',
            'hideOutput' => '0',
            'message' => 'Some Message',
            'subject' => 'Some Subject',
            'to' => $to_address,
            'fromName' => 'Some Name',
            'emailSender' => $from_address,
            'replyTo' => $from_address,
            'html' => '1',
            'failureMessage' => '<br /><h3 style=&quot;color:red&quot;>Mail Failed</h3>',
            'successMessage' => '<br /><h3 style =&quot;color:green&quot;>Mail reported successful</h3>',
            'errorHeader' => '<br />Mail error:',
            'smtpErrorHeader' => '<br />SMTP server report:',
        );
        
        $output =  $modx->runSnippet('QuickEmail',$props);
        return $output;

    }

}
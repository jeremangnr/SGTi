<?php
class Application_Service_Mail extends Application_Service_AbstractService {
    
    private static $instance;

    private function __construct() {
	parent::__initResources();
    }

    public static function getInstance() {
	if (is_null(self::$instance)) {
	    self::$instance = new Application_Service_Mail();
	}
	return self::$instance;
    }
    
    public function sendMail($html,$to,$subject){
            $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login',
                'username' => 'elfozi@gmail.com',
                'password' => 'cascada123');
            $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            $mail = new Zend_Mail();
            $mail->setBodyText($html);
            $mail->setFrom('elfozi@gmail.com', 'Tecnologo en Informatica');
            //echo $html;
            foreach($to as $element){
                $mail->addTo($element);
            }
            $mail->setSubject($subject);
            $mail->send($transport);    
    }
}
?>

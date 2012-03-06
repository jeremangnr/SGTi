<?php

class SeguridadController extends Zend_Controller_Action {

    public function preDispatch() {
        parent::preDispatch();
        
	Application_Service_Usuario::getInstance()->createRoot();
    }

    public function loginAction() {
	if ($this->getRequest()->isPost()) {
	    $adapter = new ZC_Auth_Adapter($this->_getParam('usuario'), $this->_getParam('password'));
	    $resultado = Zend_Auth::getInstance()->authenticate($adapter);

	    if (Zend_Auth::getInstance()->hasIdentity()) {
		/* $u = Zend_Auth::getInstance()->getIdentity();
		  echo "Paso ";
		  echo $u->getNombre(); */
		$this->_redirect('/');
	    } else {
		$this->view->mensaje = implode(' ', $resultado->getMessages());
	    }
	}
    }

    public function logoutAction() {
	if (Zend_Auth::getInstance()->hasIdentity()) {
	    //Limpia la indentidad existente en el plugin de Zend Auth
	    Zend_Auth::getInstance()->clearIdentity();
	    //Redirige al usuario al form de login
	    //...
	}
	$this->_redirect('/seguridad/login');
    }

}

?>

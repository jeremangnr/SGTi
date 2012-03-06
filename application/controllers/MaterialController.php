<?php

$session = new Zend_Session_Namespace();
$session->main_tab_location = 6;

class MaterialController extends Zend_Controller_Action {
    /*
     * @var Zend_Session_Namespace 
     */

    private $session;

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $entityManager;

    //Metodo que se ejecuta antes de despachar los metodos del controlador

    public function preDispatch() {
        parent::preDispatch();
        
	//Chequeo de identidad
	if (!Zend_Auth::getInstance()->hasIdentity()) {
	    $this->_redirect('/seguridad/login');
	    //Si la identidad es valida chequeo que tenga permisos para el recurso
	} else {
	    $acl = SGTi\Security\MyAcl::getInstance();
	    $rol = Zend_Auth::getInstance()->getIdentity()->getRol();

	    $recurso = $this->getRequest()->getControllerName();
	    $allowed = $acl->verificar($rol, $recurso);
	    if (!$allowed) {
		$this->_redirect('/error/denied');
	    }
	}
    }

    public function init() {
	parent::init();

	$this->session = new Zend_Session_Namespace();
	$this->session->main_tab_location = 6;
	$this->entityManager = Zend_Registry::get('doctrine')->getEntityManager();
    }

    public function mainmaterialAction() {
	if ($this->_hasParam('idmateria')) {
	    $materiaService = Application_Service_Materia::getInstance();
	    $form = new Application_Form_Materia();

	    $materia = $this->entityManager->find('SGTi\Entity\Materia', $this->_getParam('idmateria'));
	    $this->view->materia = $materia;
	}


	$planes = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio')->findAll();
	$this->view->listaplanes = $planes;
    }

    public function downloadmaterialAction() {
	$materia = $this->entityManager->find('SGTi\Entity\Material', $this->_getParam('id'));

	header('Content-Type: ' . $materia->getTipo());
	header('Content-Disposition: attachment; filename="' . $materia->getNombre() . '"');

	try {
	    readfile($materia->getPath());
	} catch (Exception $e) {
	    echo $e;
	    return;
	}

	// disable layout and view
	$this->view->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
    }

}

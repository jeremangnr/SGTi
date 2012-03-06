<?php

$session = new Zend_Session_Namespace();
$session->main_tab_location = 4;

class ForoController extends Zend_Controller_Action {
    /*
     * @var Zend_Session_Namespace 
     */

    private $session;

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $entityManager;

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

        $this->entityManager = Zend_Registry::get('doctrine')->getEntityManager();
    }

    public function mainforoAction() {


        $planes = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio')->findAll();
        $this->view->listaplanes = $planes;
    }

    public function vertemaAction() {


        $planes = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio')->findAll();
        $this->view->listaplanes = $planes;
    }

}
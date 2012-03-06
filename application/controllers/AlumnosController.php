<?php

class AlumnosController extends Zend_Controller_Action {

    /**
     *
     * @var Zend_Session_Namespace 
     */
    private $session;

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $entityManager;

    public function init() {
        parent::init();

        $this->session = new Zend_Session_Namespace();
        $this->session->main_tab_location = 1;
        $this->entityManager = Zend_Registry::get('doctrine')->getEntityManager();
    }

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

    public function verperfilAction() {
        $this->session->alu_tab_location = 1;
        $usuario = Zend_Auth::getInstance()->getIdentity();

        //traigo el alumno logueado
        $alumno = $this->entityManager->getRepository('SGTi\Entity\Alumno')->find($usuario->getPersona()->getId());

        $this->view->selectedAlumno = $alumno;
    }

    public function escolaridadAction() {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $alumno = $this->entityManager->getRepository('SGTi\Entity\Alumno')->find($usuario->getPersona()->getId());
	$this->session->alu_tab_location = 2;
        $personaService = Application_Service_Persona::getInstance(); 
        if($this->getRequest()->isPost()){
            $personaService->generarEscolaridad($alumno->getId(),true);
        }else{
            $escolaridad=$personaService->generarEscolaridad($alumno->getId(),false);
            $this->view->esco = $escolaridad;
        }

    }

    public function listaFaltasCursoAction() {
        //require_once("/library/dompdf/dompdf_config.inc.php");
        // sino vino ninguna id por parametro lo mandamos de nuevo al listado de planes
        if (!$this->_hasParam('idalumno') && !$this->_hasParam('idCurso')) {
            return $this->_redirect('/docentes/pasarlista');
        }
        $alumno = $this->entityManager->find('SGTi\Entity\Alumno', $this->_getParam('idalumno'));
        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('idCurso'));

        $contadorAlumnos = 0;
    }

    public function vercursoAction() {
	
	if (!$this->_hasParam('aluid') && $this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/adminalumnos');
        }
	
	$alumno = $this->entityManager->find('SGTi\Entity\Alumno', $this->_getParam('aluid'));
        $this->view->selectedAlumno = $alumno;
	
	$curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
	$this->view->selectedCurso = $curso;
	
	$this->view->listaAsistencias = null;
	$asistencias = $this->entityManager->getRepository('SGTi\Entity\Asistencia')->findByAlumnoCurso($curso->getId(), $alumno->getId());		
	$this->view->listaAsistencias = $asistencias;
        
	$this->view->listaCalificaciones = null;	
	$calificaciones = $this->entityManager->getRepository('SGTi\Entity\Calificacion')->findByAlumnoCurso($curso->getId(), $alumno->getId());
	$this->view->listaCalificaciones = $calificaciones;
	
    }
    
    public function infocursoecAction() {

        if (!$this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/admindocentes');
        }
	
	$alumno = $this->entityManager->find('SGTi\Entity\Alumno', $this->_getParam('aluid'));
        $this->view->selectedAlumno = $alumno;

        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $this->view->selectedCurso = $curso;
        $alumnos = $this->entityManager->getRepository('SGTi\Entity\Alumno')->findAll();
        $this->view->listahorarios = $curso->getHorarios();
    }

    public function infocursodmAction() {

        if (!$this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/admindocentes');
        }
	
	$alumno = $this->entityManager->find('SGTi\Entity\Alumno', $this->_getParam('aluid'));
        $this->view->selectedAlumno = $alumno;
	
        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $this->view->selectedCurso = $curso;
    }
}

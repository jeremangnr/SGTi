<?php

class DocentesController extends Zend_Controller_Action {

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
        $this->session = new Zend_Session_Namespace();
        $this->session->main_tab_location = 2;
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

    public function pasarlistaAction() {
        $this->session->doc_tab_location = 1;
        $usuario = Zend_Auth::getInstance()->getIdentity();

        //traigo el docente logueado
        $docente = $this->entityManager->getRepository('SGTi\Entity\Docente')->find($usuario->getPersona()->getId());
        $this->view->listaCursos = null;
        if (count($docente->getCursos()) > 0) {
            $this->view->listaCursos = $docente->getCursos();
        }

        if ($this->getRequest()->isPost()) {
            //traigo el id del curso
            $cursoId = $this->_getParam('cursos');
            //traigo los id de los alumnos que asistieron
            $alumnos = $this->_getParam('asistencias');
            //guardo la fecha
            $fecha = $this->_getParam('fecha');

            //traigo todos los alumnos en el campo hidden
            $totalAlumnos = $this->_getParam('allAlumnos');

            $cursoService = Application_Service_Curso::getInstance();

            $pudoAgregar = $cursoService->agregarClase($cursoId, $alumnos, $totalAlumnos, $fecha);

            $this->view->pudoAgregar = $pudoAgregar;
        }
    }

    public function eventocalificacionAction() {
        $this->session->doc_tab_location = 2;
        $form = new Application_Form_EventoCalificacion();
        $this->view->form = $form;
        $usuario = Zend_Auth::getInstance()->getIdentity();

        //traigo el docente logueado
        $docente = $this->entityManager->getRepository('SGTi\Entity\Docente')->find($usuario->getPersona()->getId());
        $this->view->listaCursos = null;
        if (count($docente->getCursos()) > 0) {
            $this->view->listaCursos = $docente->getCursos();
            $this->view->listaTipos = $this->entityManager->getRepository('SGTi\Entity\TipoEventoCalificacion')->findAll();
        }

        if ($this->getRequest()->isPost()) {
            //no se por que mierda no toma los valores del form asi que los cargo a lo bestia
	    //$data = $form->getValues();	    
            $evCalData['nombre'] = $this->entityManager->getRepository('SGTi\Entity\TipoEventoCalificacion')->find($this->_getParam('tipoEvento'));
            $evCalData['notaMax'] = $this->_getParam('notaMax');
            $evCalData['notaAprobacion'] = $this->_getParam('notaAprobacion');
            $evCalData['descripcion'] = $this->_getParam('descripcion');
            //traigo todos los alumnos en el parametro hidden
            $alumnos = $this->_getParam('allAlumnos');
            //traigo las notas
            $notas = $this->_getParam('nota');
            //traigo los que asistieron
            $observaciones = $this->_getParam('observaciones');

            $idCurso = $this->_getParam('curso-select');

            $cursoService = Application_Service_Curso::getInstance();
            //agrego el evento de calificacion y las notas de los alumnos
            $pudoAgregar = $cursoService->agregarNota($alumnos, $notas, $observaciones, $idCurso, $evCalData);

            $this->view->pudoAgregar = $pudoAgregar;
            $this->view->eventoCalificacionIngresado = $evCalData;

            if ($pudoAgregar) {
                $form->reset();
            }
        }
    }

    public function subirmaterialAction() {
        $this->session->doc_tab_location = 3;
        $materias = $this->entityManager->getRepository('SGTi\Entity\Materia')->findAll();
        $this->view->materias = $materias;
	
        $materia = $this->_getParam('materia');
        $materialService = Application_Service_Material::getInstance();

        if ($this->getRequest()->isPost()) {
            $transferAdapter = new Zend_File_Transfer_Adapter_Http();
	    
            $pudoAgregar = $materialService->uploadMaterial($transferAdapter, $materia);
	    
            $this->view->pudoAgregar = $pudoAgregar;
        }
    }
}

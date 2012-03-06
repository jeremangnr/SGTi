<?php

class AdministracionController extends Zend_Controller_Action {

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
        $this->session->main_tab_location = 3;
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

    public function adminalumnosAction() {
        $this->session->admin_tab_location = 3;
        $alumnos = $this->entityManager->getRepository('SGTi\Entity\Alumno')->findAll();
        $this->view->listaalumnos = $alumnos;
    }

    public function admindocentesAction() {
        $this->session->admin_tab_location = 2;
        $docentes = $this->entityManager->getRepository('SGTi\Entity\Docente')->findAll();
        $this->view->listadocentes = $docentes;
    }

    public function adminplanestudioAction() {
        $this->session->admin_tab_location = 1;
        $planes = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio')->findAll();
        $this->view->listaplanes = $planes;
    }

    public function noticiasAction() {
        $this->session->admin_tab_location = 6;
        $noticias = $this->entityManager->getRepository('SGTi\Entity\Noticia')->findAll();
        $this->view->listanoticias = $noticias;
    }

    public function adminadministrativosAction() {
        $this->session->admin_tab_location = 4;
        $administrativos = $this->entityManager->getRepository('SGTi\Entity\Administrativo')->findAll();
        $this->view->listaadministrativos = $administrativos;
    }

    public function salonesAction() {
        $this->session->admin_tab_location = 7;
        $salones = $this->entityManager->getRepository('SGTi\Entity\Salon')->findAll();
        $this->view->listasalones = $salones;
    }

    public function agregaralumnoAction() {
        $form = new Application_Form_Alumno();
        $personaService = Application_Service_Persona::getInstance();
        if ($this->_getParam('descargar') == 1) {
            /* TODO ESTO TIENE QUE IR ADENTRO DEL SERVICE PIZZOLANTI. LA PUTA MADRE */
            require_once('dompdf/dompdf_config.inc.php');
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->pushAutoloader('DOMPDF_autoload', '');
            $dompdf = new DOMPDF();
            $dompdf->load_html($personaService->generarenotainscripcion($this->_getParam('id'), $this->_getParam('req')));
            $dompdf->render();
            $dompdf->stream("inscripcion.pdf");
        }
        $this->view->form = $form;
        // verifico que venga por POST (esta enviando datos)
        if ($this->getRequest()->isPost()) {
            // veo que el form sea valido (que los valores requeridos estan y demas validacion que tenga el form)
            if ($form->isValid($this->getRequest()->getParams())) {
                // paso el array de valores del form al PersonaService
                $alumnoData = $form->getValues();
                $todosLosRequisitos = "no";
                $requisitos = array();
                $todosLosRequisitos = "si";
                if ($this->_getParam('cedula') != null) {
                    $requisitos[0] = 1;
                } else {
                    $requisitos[0] = 0;
                    $todosLosRequisitos = "no";
                }
                if ($this->_getParam('carne') != null) {
                    $requisitos[1] = 1;
                } else {
                    $requisitos[1] = 0;
                    $todosLosRequisitos = "no";
                }
                if ($this->_getParam('foto') != null) {
                    $requisitos[2] = 1;
                } else {
                    $requisitos[2] = 0;
                    $todosLosRequisitos = "no";
                }
                if ($this->_getParam('pase') != null) {
                    $requisitos[3] = 1;
                } else {
                    $requisitos[3] = 0;
                    $todosLosRequisitos = "no";
                }
                $this->view->req = $requisitos;

                // recordar que saveAlumno devuelve true o false. asi se si mostrar error o no en la vista
                $pudoAgregar = $personaService->saveAlumno($alumnoData, $todosLosRequisitos);
                $this->view->pudoAgregar = $pudoAgregar;
                $form->reset();
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregarplanAction() {
        $form = new Application_Form_PlanDeEstudio();
        $planDeEstudioService = Application_Service_PlanDeEstudio::getInstance();

        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $planData = $form->getValues();
                $pudoAgregar = $planDeEstudioService->savePlanDeEstudio($planData);

                $this->view->pudoAgregar = $pudoAgregar;
                $this->view->planIngresado = $planData;

                //esto lo hago para que no me siga mostrando los valores despues que submitee el form
                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregarperiodoAction() {
        if (!$this->_hasParam('planid')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $form = new Application_Form_Periodo();
        $planService = Application_Service_PlanDeEstudio::getInstance();
        $this->view->form = $form;
        $this->view->selectedPlan = $this->entityManager->find('SGTi\Entity\PlanDeEstudio', $this->_getParam('planid'));

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {
                $periodoData = $form->getValues();
                //actualizo el plan con el nuevo periodo
                $pudoAgregar = $planService->agregarPeriodoPlan($this->_getParam('planid'), $periodoData);

                $this->view->pudoAgregar = $pudoAgregar;
                $this->view->periodoIngresado = $periodoData;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregarmateriaAction() {
        $form = new Application_Form_Materia();
        $this->view->selectedPeriodo = $this->entityManager->find('SGTi\Entity\Periodo', $this->_getParam('perid'));

        $periodoService = Application_Service_Periodo::getInstance();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {
                $materiaData = $form->getValues();

                $pudoAgregar = $periodoService->agregarMateriaPeriodo($this->_getParam('perid'), $materiaData);

                $this->view->pudoAgregar = $pudoAgregar;
                $this->view->materiaIngresada = $materiaData;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregarsalonAction() {
        $form = new Application_Form_Salon();
        $salonService = Application_Service_Salon::getInstance();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {
                $salonData = $form->getValues();

                $pudoAgregar = $salonService->saveSalon($salonData);
                $this->view->pudoAgregar = $pudoAgregar;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregarnoticiaAction() {
        $form = new Application_Form_Noticia();
        $usuarioService = Application_Service_Usuario::getInstance();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $noticiaData = $form->getValues();
                $pudoAgregar = $usuarioService->agregarNoticia($noticiaData);
                $this->view->pudoAgregar = $pudoAgregar;
                if ($this->_getParam('enviar') == 1) {
                    if ($this->_getParam('usuarios') == 1) {
                        $mails = $this->entityManager->getRepository('SGTi\Entity\Alumno')->getAllMails();
                        $mailService = Application_Service_Mail::getInstance();
                        $mailService->sendMail($this->_getParam("contenido"), $mails, "Noticia");
                    } else {
                        if ($this->_getParam('usuarios') == 2) {
                            $mails = $this->entityManager->getRepository('SGTi\Entity\Docente')->getAllMails();
                            $mailService = Application_Service_Mail::getInstance();
                            $mailService->sendMail($this->_getParam("contenido"), $mails, "Noticia");
                        } else {
                            $mailsA = $this->entityManager->getRepository('SGTi\Entity\Alumno')->getAllMails();
                            $mailsD = $this->entityManager->getRepository('SGTi\Entity\Docente')->getAllMails();
                            $todosMails = array_merge((array) $mailsA, (array) $mailsD);
                            $mailService = Application_Service_Mail::getInstance();
                            $mailService->sendMail($this->_getParam("contenido"), $todosMails, "Noticia");
                        }
                    }
                }
                if ($pudoAgregar) {
                    $form->reset();
                } else {
                    $this->view->errors = $form->getMessages();
                }
            }
        }
    }

    public function agregardocenteAction() {
        $form = new Application_Form_Docente();
        $personaService = Application_Service_Persona::getInstance();

        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $docenteData = $form->getValues();
                $pudoAgregar = $personaService->saveDocente($docenteData);

                $this->view->pudoAgregar = $pudoAgregar;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregaradministrativoAction() {
        $form = new Application_Form_Administrativo();
        $personaService = Application_Service_Persona::getInstance();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {

                $administrativoData = $form->getValues();
                $pudoAgregar = $personaService->saveAdministrativo($administrativoData);
                $this->view->pudoAgregar = $pudoAgregar;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function infoplanAction() {
        // sino vino ninguna id por parametro lo mandamos de nuevo al listado de planes
        if (!$this->_hasParam('planid')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $planService = Application_Service_PlanDeEstudio::getInstance();
        $form = new Application_Form_PlanDeEstudio();
        $plan = $this->entityManager->find('SGTi\Entity\PlanDeEstudio', $this->_getParam('planid'));

        $this->view->selectedPlan = $plan;
        $this->view->form = $form;
        // mismo verso de siempre, verificamos si esta mandando datos, y que estos sean validos
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                // aca hacemos el update
                $updatedPlanData = $form->getValues();
                $updatedPlan = $planService->updatePlan($this->_getParam('planid'), $updatedPlanData);
            } else {
                // si hubo errores al modificar, ingreso algun dato mal o algo, lo tiramos aca
                $this->view->errors = $form->getMessages();
            }
        } else {
            $formData = array(
                'nombre' => $plan->getNombre(),
                'anio' => $plan->getAnio(),
                'descripcion' => $plan->getDescripcion(),
                'notaExoneracion' => $plan->getNotaExoneracion(),
                'notaAprobacion' => $plan->getNotaAprobacion(),
                'notaMaxima' => $plan->getNotaMaxima()
            );

            if (isset($plan)) {
                $form->populate($formData);
            }
        }
    }

    public function infoperiodoAction() {
        // sino vino ninguna id por parametro lo mandamos de nuevo al listado de planes
        if (!$this->_hasParam('idperiodo')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $periodoService = Application_Service_Periodo::getInstance();
        $form = new Application_Form_Periodo();
        $periodo = $this->entityManager->find('SGTi\Entity\Periodo', $this->_getParam('idperiodo'));

        $this->view->selectedPeriodo = $periodo;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $updatedPeriodoData = $form->getValues();
                $updatedPeriodo = $periodoService->updatePeriodo($this->_getParam('idperiodo'), $updatedPeriodoData);
            } else {
                // si hubo errores al modificar, ingreso algun dato mal o algo, lo tiramos aca
                $this->view->errors = $form->getMessages();
            }
        } else {
            $formData = array(
                'tipo' => $periodo->getTipo(),
                'numero' => $periodo->getNumero()
            );

            if (isset($periodo)) {
                $form->populate($formData);
            }
        }
    }

    public function agregarrolAction() {
        $form = new Application_Form_Rol();
        $rolService = Application_Service_Rol::getInstance();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {

                $rolData = $form->getValues();
                $pudoAgregar = $rolService->saveRol($rolData);
                $this->view->pudoAgregar = $pudoAgregar;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregarcursoAction() {
        $form = new Application_Form_Curso();
        $materiaService = Application_Service_Materia::getInstance();

        $materia = $this->entityManager->find('SGTi\Entity\Materia', $this->_getParam('idmateria'));
        $this->view->form = $form;
        $this->view->materia = $materia;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $cursoData = $form->getValues();

                $pudoAgregar = $materiaService->agregarCursoMateria($cursoData, $materia);

                $this->view->pudoAgregar = $pudoAgregar;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregareventocalificacionAction() {

        $form = new Application_Form_EventoCalificacion();
        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('idcurso'));

        $this->view->form = $form;
        $this->view->selectedCurso = $curso;
        $this->view->listaTipos = $this->entityManager->getRepository('SGTi\Entity\TipoEventoCalificacion')->findAll();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $evCalData = $form->getValues();

                //traigo todos los alumnos en el parametro hidden
                $alumnos = $this->_getParam('allAlumnos');
                //traigo las notas
                $notas = $this->_getParam('notas');
                //traigo los que asistieron
                $observaciones = $this->_getParam('observaciones');

                $cursoService = Application_Service_Curso::getInstance();

                //agrego el evento de calificacion y las notas de los alumnos
                $pudoAgregar = $curso->agregarNota($alumnos, $notas, $observaciones, $idCurso, $evCalData);

                $this->view->pudoAgregar = $pudoAgregar;
                $this->view->eventoCalificacionIngresado = $evCalData;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregarhorarioAction() {
        $form = new Application_Form_Horario();
        $cursoService = Application_Service_Curso::getInstance();
        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));

        $this->view->selectedCurso;

        $salones = $this->entityManager->getRepository('SGTi\Entity\Salon')->findAll();

        $this->view->listaSalones = $salones;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $horarioData = $form->getValues();
                $pudoAgregar = $cursoService->agregarHorarioCurso($this->_getParam('idcurso'), $this->_getParam('salones'), $horarioData);

                $this->view->pudoAgregar = $pudoAgregar;
                $this->view->horarioIngresado = $horarioData;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function infomateriaAction() {
        if (!$this->_hasParam('idmateria')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $materiaService = Application_Service_Materia::getInstance();
        $form = new Application_Form_Materia();
        $materia = $this->entityManager->find('SGTi\Entity\Materia', $this->_getParam('idmateria'));

        $this->view->selectedMateria = $materia;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $updatedMateriaData = $form->getValues();
                $updatedMateria = $materiaService->updateMateria($this->_getParam('idmateria'), $updatedMateriaData);
            } else {
                // si hubo errores al modificar, ingreso algun dato mal o algo, lo tiramos aca
                $this->view->errors = $form->getMessages();
            }
        } else {
            $formData = array(
                'nombre' => $materia->getNombre(),
                'creditos' => $materia->getCreditos(),
                'tipoAprobacion' => $materia->getTipoAprobacion()
            );

            if (isset($materia)) {
                $form->populate($formData);
            }
        }
    }
    public function infoalumnoAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminalumnos');
        }

        $personaService = Application_Service_Persona::getInstance();
        $form = new Application_Form_Alumno();
        $alumno = $this->entityManager->find('SGTi\Entity\Alumno', $this->_getParam('id'));

        $this->view->selectedAlumno = $alumno;
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $updatedAlumnoData = $form->getValues();
                $updatedAlumno = $personaService->updateAlumno($this->_getParam('id'), $updatedAlumnoData);
            } else {
                $this->view->errors = $form->getMessages();
            }
        } else {
            $formData = array(
                'ci' => $alumno->getCi(),
                'nombre' => $alumno->getNombre(),
                'apellido' => $alumno->getApellido(),
                'fechaNac' => $alumno->getFechaNac()->format('d-m-Y'),
                'telefono' => $alumno->getTelefono(),
                'celular' => $alumno->getCelular(),
                'localidad' => $alumno->getLocalidad(),
                'mail' => $alumno->getMail()
            );

            if (isset($alumno)) {
                $form->populate($formData);
            }
        }
    }

    public function infoadministrativoAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $personaService = Application_Service_Persona::getInstance();
        $form = new Application_Form_Administrativo();
        $administrativo = $this->entityManager->find('SGTi\Entity\Administrativo', $this->_getParam('id'));

        $this->view->selectedAdministrativo = $administrativo;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $updatedAdministrativoData = $form->getValues();
                $updatedAdministrativo = $personaService->updateAdministrativo($this->_getParam('id'), $updatedAdministrativoData);
            } else {
                $this->view->errors = $form->getMessages();
            }
        } else {
            $formData = array(
                'ci' => $administrativo->getCi(),
                'nombre' => $administrativo->getNombre(),
                'apellido' => $administrativo->getApellido(),
                'fechaNac' => $administrativo->getFechaNac()->format('d-m-Y'),
                'telefono' => $administrativo->getTelefono(),
                'celular' => $administrativo->getCelular(),
                'localidad' => $administrativo->getLocalidad(),
                'mail' => $administrativo->getMail()
            );

            if (isset($administrativo)) {
                $form->populate($formData);
            }
        }
    }

    public function infodocenteAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/admindocentes');
        }

        $personaService = Application_Service_Persona::getInstance();
        $form = new Application_Form_Docente();
        $docente = $this->entityManager->find('SGTi\Entity\Docente', $this->_getParam('id'));

        $this->view->selectedDocente = $docente;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $updatedDocenteData = $form->getValues();
                $updatedDocente = $personaService->updateDocente($this->_getParam('id'), $updatedDocenteData);
            } else {
                $this->view->errors = $form->getMessages();
            }
        } else {
            $formData = array(
                'ci' => $docente->getCi(),
                'nombre' => $docente->getNombre(),
                'apellido' => $docente->getApellido(),
                'fechaNac' => $docente->getFechaNac()->format('d-m-Y'),
                'telefono' => $docente->getTelefono(),
                'celular' => $docente->getCelular(),
                'localidad' => $docente->getLocalidad(),
                'mail' => $docente->getMail()
            );

            if (isset($docente)) {
                $form->populate($formData);
            }
        }
    }

    public function eliminaradministrativoAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $personaService = Application_Service_Persona::getInstance();
        $administrativo = $this->entityManager->find('SGTi\Entity\Administrativo', $this->_getParam('id'));

        $personaService->removeAdministrativo($administrativo);

        return $this->_redirect('/administracion/adminadministrativos');
    }

    public function eliminardocenteAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $personaService = Application_Service_Persona::getInstance();
        $docente = $this->entityManager->find('SGTi\Entity\Docente', $this->_getParam('id'));

        $personaService->removeDocente($docente);

        return $this->_redirect('/administracion/admindocentes');
    }

    public function eliminaralumnoAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $personaService = Application_Service_Persona::getInstance();
        $alumno = $this->entityManager->find('SGTi\Entity\Alumno', $this->_getParam('id'));

        $personaService->removeDocente($alumno);

        return $this->_redirect('/administracion/adminalumnos');
    }

    public function eliminarnoticiaAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $noticiaService = Application_Service_Noticia::getInstance();
        $noticia = $this->entityManager->find('SGTi\Entity\Noticia', $this->_getParam('id'));

        $noticiaService->removeNoticia($noticia);

        return $this->_redirect('/administracion/noticias');
    }

    public function eliminarrolAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $rolService = Application_Service_Rol::getInstance();
        $rol = $this->entityManager->find('SGTi\Entity\Rol', $this->_getParam('id'));

        $rolService->removeRol($rol);

        return $this->_redirect('/administracion/adminroles');
    }

    public function eliminarplanAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $planService = Application_Service_PlanDeEstudio::getInstance();
        $plan = $this->entityManager->find('SGTi\Entity\Plan', $this->_getParam('id'));

        $planService->removePlan($plan);

        return $this->_redirect('/administracion/adminplanestudio');
    }

    public function eliminarperiodoAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $periodoService = Application_Service_Periodo::getInstance();
        $periodo = $this->entityManager->find('SGTi\Entity\Periodo', $this->_getParam('id'));

        $periodoService->removePeriodo($periodo);

        return $this->_redirect('/administracion/adminplanestudio');
    }

    public function eliminarmateriaAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $materiaService = Application_Service_Materia::getInstance();
        $materia = $this->entityManager->find('SGTi\Entity\Materia', $this->_getParam('id'));

        $materiaService->removeMateria($materia);

        return $this->_redirect('/administracion/adminplanestudio');
    }

    public function eliminarhorarioAction() {

        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $horarioService = Application_Service_Horario::getInstance();
        $horario = $this->entityManager->find('SGTi\Entity\Horario', $this->_getParam('id'));

        $horarioService->removeHorario($horario);

        //return $this->_redirect('/administracion/adminplanestudio');
    }

    public function eliminarsalonAction() {
        if (!$this->_hasParam('salonid')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }

        $salonService = Application_Service_Salon::getInstance();
        $salon = $this->entityManager->find('SGTi\Entity\Salon', $this->_getParam('salonid'));

        $salonService->removeSalon($salon);

        return $this->_redirect('/administracion/salones');
    }

    public function eliminarrequisitoAction() {
        if (!$this->_hasParam('requisitoid')) {
            return $this->_redirect('/administracion/adminadministrativos');
        }
    }

    public function vercursosalumnoAction() {
	if (!$this->_hasParam('aluid')) {
            return $this->_redirect('/administracion/adminalumnos');
        }

        $this->view->selectedAlumno = $this->entityManager->find('SGTi\Entity\Alumno', $this->_getParam('aluid'));
        $this->view->listaCursos = null;
        if ($alumno->getInscripcion() != null) {
            $this->view->listaCursos = $alumno->getInscripcion()->getInscripcionesCurso();
        }

        $this->view->selectedInscripcionCurso = null;
        if ($this->_hasParam('cursoid')) {
            $inscripcionCurso = $this->entityManager->find('SGTi\Entity\InscripcionCurso', $this->_getParam('cursoid'));
            $this->view->selectedInscripcionCurso = $inscripcionCurso;
        }
    }

    public function enviarmailAction() {
        $this->view->mails = $this->_getParam('mails');
        if ($this->getRequest()->isPost()) {
            $arraya = array($this->_getParam('mail'));
            $mailService = Application_Service_Mail::getInstance();
            $mailService->sendMail($this->_getParam("contenido"), $arraya, $this->_getParam("asunto"));
        }
    }

    public function inscribiralumnosaplanAction() {
        $planService = Application_Service_PlanDeEstudio::getInstance();

        $alumnos = $this->entityManager->getRepository('SGTi\Entity\Alumno')->findAll();
        $planes = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio')->findAll();

        $this->view->listaalumnos = null;

        //controlo que solo liste los alumnos que aún no han sido inscriptos
        foreach ($alumnos as $alumno) {
            if ($alumno->getInscripcion() == null) {
                $this->view->listaalumnos[] = $alumno;
            }
        }

        $this->view->listaplanes = $planes;

        if ($this->getRequest()->isPost() && $this->_hasParam('alumnos') && $this->_hasParam('planes')) {
            $alumnos = $this->_getParam('alumnos');
            $idPlan = $this->_getParam('planes');

            $pudoAgregar = $planService->inscribirAlumnoPlan($alumnos, $idPlan);

            $this->view->pudoAgregar = $pudoAgregar;
        }
    }

    public function inscribiralumnosacursoAction() {
        $planes = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio')->findAll();
        $alumnos = $this->entityManager->getRepository('SGTi\Entity\Alumno')->findAll();

        $this->view->listaalumnos = $alumnos;
        $this->view->listaplanes = $planes;

        if ($this->getRequest()->isPost() && $this->_getParam('alumnos') != null && $this->_getParam('cursos') != null) {
            $cursoService = Application_Service_Curso::getInstance();

            $alumnos = $this->_getParam('alumnos');
            $idCurso = $this->_getParam('cursos');

            $pudoAgregar = $cursoService->inscribirAlumnosCurso($alumnos, $idCurso);

            $this->view->pudoAgregar = $pudoAgregar;
        }
    }

    public function infosalonAction() {
        if (!$this->_hasParam('idsalon')) {
            return $this->_redirect('/administracion/adminsalones');
        }

        $salonService = Application_Service_Salon::getInstance();
        $form = new Application_Form_Salon();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                if ($form->isValid($this->getRequest()->getParams())) {
                    $updatedSalonData = $form->getValues();

                    $updatedSalon = $salonService->updateSalon($this->_getParam('idsalon'), $updatedSalonData);

                    $this->view->form = $form;
                    $this->view->selectedSalon = $updatedSalon;
                } else {
                    $this->view->errors = $form->getMessages();
                }
            }
        } else {
            $salon = $this->entityManager->find('SGTi\Entity\Salon', $this->_getParam('idsalon'));
            $this->view->selectedSalon = $salon;

            $formData = array(
                'nombre' => $salon->getNombre(),
                'capacidad' => $salon->getCapacidad()
            );

            if (isset($salon)) {
                $form->populate($formData);
            }

            $this->view->form = $form;
            $this->view->selectedSalon = $salon;
        }
    }

    public function infocursoAction() {
        if (!$this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/admindocentes');
        }
        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));

        $this->view->selectedCurso = $curso;
        $this->view->listaEventos = $curso->getEventosCalificacion();
    }

    public function infocursoecAction() {

        if (!$this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/admindocentes');
        }


        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $this->view->selectedCurso = $curso;
        $alumnos = $this->entityManager->getRepository('SGTi\Entity\Alumno')->findAll();
        $this->view->listahorarios = $curso->getHorarios();
    }

    public function infocursodAction() {

        if (!$this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/admindocentes');
        }

        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $this->view->selectedCurso = $curso;
    }

    public function inforolAction() {
        if (!$this->_hasParam('id')) {
            return $this->_redirect('/administracion/adminroles');
        }

        $rol = $this->entityManager->find('SGTi\Entity\Rol', $this->_getParam('id'));
        $form = new Application_Form_Rol();

        $this->view->form = $form;
        $this->view->selectedRol = $rol;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                
            }
        } else {
            
        }
    }

    public function modificarnoticiaAction() {
        if (!$this->_hasParam('noticiaid')) {
            return $this->_redirect('/administracion/noticias');
        }

        $noticiaService = Application_Service_Noticia::getInstance();
        $form = new Application_Form_Noticia();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $updatedNoticiaData = $form->getValues();
                $updatedNoticia = $noticiaService->updateNoticia($this->_getParam('noticiaid'), $updatedNoticiaData);

                $this->view->form = $form;
                $this->view->selectedNoticia = $updatedNoticia;
            } else {
                $this->view->errors = $form->getMessages();
            }
        } else {
            $noticia = $this->entityManager->find('SGTi\Entity\Noticia', $this->_getParam('noticiaid'));

            $formData = array(
                'titulo' => $noticia->getTitulo(),
                'contenido' => $noticia->getContenido()
                    //'usuario' => $noticia->getUsuario(),
            );

            if (isset($noticia)) {
                $form->populate($formData);
            }

            $this->view->form = $form;
        }
    }

    public function modificarsalonAction() {
        if (!$this->_hasParam('salonid')) {
            return $this->_redirect('/administracion/salones');
        }

        $salonService = Application_Service_Salon::getInstance();
        $form = new Application_Form_Salon();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $updatedSalonData = $form->getValues();
                $updatedSalon = $salonService->updateSalon($this->_getParam('salonid'), $updatedSalonData);

                $this->view->form = $form;
                $this->view->selectedSalon = $updatedSalon;
            } else {
                $this->view->errors = $form->getMessages();
            }
        } else {
            $salon = $this->entityManager->find('SGTi\Entity\Salon', $this->_getParam('salonid'));

            $formData = array(
                'nombre' => $salon->getNombre(),
                'capacidad' => $salon->getCapacidad()
            );

            if (isset($salon)) {
                $form->populate($formData);
            }

            $this->view->form = $form;
        }
    }

    public function agregardocenteacursoAction() {
        $planes = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio')->findAll();
        $this->view->listaplanes = $planes;

        if ($this->getRequest()->isPost() && $this->_getParam('selectedMaterias') != null && $this->_getParam('docenteid') != null) {
            $cursoService = Application_Service_Curso::getInstance();
            $this->view->listaplanes = $planes;

            $docente = $this->_getParam('docenteid');
            $materias = $this->_getParam('selectedMaterias');
            $pudoAgregar = $cursoService->agregarDocenteCurso($docente, $materias);
            $this->view->pudoAgregar = $pudoAgregar;
        }
    }

    public function modificarhorarioAction() {
        if (!$this->_hasParam('horarioid')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $salones = $this->entityManager->getRepository('SGTi\Entity\Salon')->findAll();
        $this->view->listaSalones = $salones;

        $horarioService = Application_Service_Horario::getInstance();
        $form = new Application_Form_Horario();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $updatedHorarioData = $form->getValues();
                $updatedHorario = $horarioService->updateHorario($this->_getParam('horarioid'), $this->_getParam('salones'), $updatedHorarioData);

                $this->view->form = $form;
                $this->view->selectedHorario = $updatedHorario;
            } else {
                $this->view->errors = $form->getMessages();
            }
        } else {
            $horario = $this->entityManager->find('SGTi\Entity\Horario', $this->_getParam('horarioid'));

            $formData = array(
                'horaInicio' => $horario->getHoraInicio()->format('H:i:s'),
                'horaFin' => $horario->getHoraFin()->format('H:i:s'),
                'dia' => $horario->getDia()
            );

            if (isset($horario)) {
                $form->populate($formData);
            }

            $this->view->form = $form;
        }
    }

    public function cambiarcontrasenaAction() {
        if (!$this->_hasParam('personaid')) {
            return $this->_redirect('/administracion/admindocentes');
        }

        $personaService = Application_Service_Persona::getInstance();
        $form = new Application_Form_Usuario();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $updatedUsuarioData = $form->getValues();
                $updatedUsuario = $personaService->updatePassword($this->_getParam('personaid'), $updatedUsuarioData);

                $this->view->form = $form;
                $this->view->pudoAgregar = $updatedUsuario;
            } else {
                $this->view->errors = $form->getMessages();
            }
        } else {
            $persona = $this->entityManager->find('SGTi\Entity\Persona', $this->_getParam('personaid'));

            $formData = array(
                'nombre' => $persona->getUsuario()->getNombre(),
                'oldpass' => "",
                'newpass' => ""
            );

            if (isset($persona)) {
                $form->populate($formData);
            }

            $this->view->form = $form;
        }
    }

    public function agregarpreviaturaAction() {
        if (!$this->_hasParam('idmateria')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $materiaService = Application_Service_Materia::getInstance();

        $materia = $this->entityManager->find('SGTi\Entity\Materia', $this->_getParam('idmateria'));
        $this->view->selectedMateria = $materia;

        //$this->view->listaMaterias = null;
        $this->view->listaMaterias = $this->entityManager->getRepository('SGTi\Entity\Materia')->findPreviaturasDisponibles($materia->getId(), $materia->getPeriodo()->getNumero());

        if ($this->getRequest()->isPost() && $this->_getParam('materias') != null) {
            if (true) {

                $materias = $this->_getParam('materias');
                $pudoAgregar = $materiaService->agregarPreviaturaMateria($materia, $materias);

                $this->view->pudoAgregar = $pudoAgregar;
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function generarescolaridadAction() {
        if (!$this->_hasParam('idalumno')) {
            return $this->_redirect('/administracion/adminalumnos');
        }
        $personaService = Application_Service_Persona::getInstance();
        if ($this->getRequest()->isPost()) {
            $personaService->generarEscolaridad($this->_getParam('idalumno'), true);
            $this->view->idAlumno = $this->_getParam('idalumno');
        } else {
            $escolaridad = $personaService->generarEscolaridad($this->_getParam('idalumno'), false);
            $this->view->esco = $escolaridad;
        }
    }

    public function eliminarmaterialAction() {
        $materiaService = Application_Service_Materia::getInstance();
        $material = $this->entityManager->find('SGTi\Entity\Material', $this->_getParam('materialid'));
        $materia = $this->entityManager->find('SGTi\Entity\Materia', $this->_getParam('materiaid'));
        
        $materiaService->removeMaterialMateria($materia, $material);
        
        return $this->_redirect('/administracion/infocursodm/cursoid/' . $this->_getParam('materiaid'));
    }

    public function verasistenciasAction() {
        if (!$this->_hasParam('aluid') && $this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/adminalumnos');
        }

        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $idAlumno = $this->_getParam('aluid');

        $this->view->listaAsistencias = null;
        $asistencias = $this->entityManager->getRepository('SGTi\Entity\Asistencia')->findByAlumnoCurso($curso->getId(), $idAlumno);
        $this->view->listaAsistencias = $asistencias;
    }

    public function vercalificacionesAction() {
        if (!$this->_hasParam('aluid') && $this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/adminalumnos');
        }

        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $idAlumno = $this->_getParam('aluid');

        $this->view->listaCalificaciones = null;
        $calificaciones = $this->entityManager->getRepository('SGTi\Entity\Calificacion')->findByAlumnoCurso($curso->getId(), $idAlumno);
        $this->view->listaCalificaciones = $calificaciones;
    }

    public function agregarrequisitoAction() {
        $form = new Application_Form_RequisitoInscripcion();
        $planService = Application_Service_PlanDeEstudio::getInstance();
        $this->view->form = $form;
        $this->view->plan = $this->entityManager->find('SGTi\Entity\PlanDeEstudio', $this->_getParam('planid'));

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $requisitoData = $form->getValues();
                //actualizo el plan con el nuevo periodo
                $pudoAgregar = $planService->agregarRequisitoPlan($this->_getParam('planid'), $requisitoData['nombre']);

                $this->view->pudoAgregar = $pudoAgregar;

                if ($pudoAgregar) {
                    $form->reset();
                }
            } else {
                $this->view->errors = $form->getMessages();
            }
        }
    }

    public function agregartipoevcalAction() {
        $eventoCalService = Application_Service_EventoCalificacion::getInstance();
        $this->view->listaTipos = $this->entityManager->getRepository('SGTi\Entity\TipoEventoCalificacion')->findAll();

        if ($this->getRequest()->isPost()) {
            $pudoAgregar = $eventoCalService->saveTipoCalificacion($this->_getParam("eventoCalificacion"));
            $this->view->pudoAgregar = $pudoAgregar;
            $this->view->listaTipos = $this->entityManager->getRepository('SGTi\Entity\TipoEventoCalificacion')->findAll();
        }
    }

    public function eliminartipocalAction() {
        $eventoCalService = Application_Service_EventoCalificacion::getInstance();
        $tipoCal = $this->entityManager->find('SGTi\Entity\TipoEventoCalificacion', $this->_getParam('id'));
        $eventoCalService->removeTipoCalificacion($tipoCal);

        $this->_redirect('administracion/agregartipoevcal');
    }

    public function subirmaterialAction() {
        $materias = $this->entityManager->getRepository('SGTi\Entity\Materia')->findAll();
        $this->view->materias = $materias;
        
        $materialService = Application_Service_Material::getInstance();
        
        if ($this->getRequest()->isPost()) {
            $materia = $this->_getParam('materia');            
            $upload = new Zend_File_Transfer_Adapter_Http();
            
            $pudoAgregar = $materialService->uploadMaterial($upload, $materia);
            $this->view->pudoAgregar = $pudoAgregar;
        }
    }

    public function editarnotasAction() {
        if (!$this->_hasParam('aluid') && $this->_hasParam('inscursoid')) {
            return $this->_redirect('/administracion/adminalumnos');
        }
        
        $insCurso = $this->entityManager->find('SGTi\Entity\InscripcionCurso', $this->_getParam('inscursoid'));
        $this->view->selectedInsCurso = $insCurso;

        if ($this->getRequest()->isPost()) {
            $cursoService = Application_Service_Curso::getInstance();
            $pudoAgregar = $cursoService->actualizarInscripcionCurso($insCurso, $this->_getParam('estado'), $this->_getParam('notaAprobacion'));
            $this->view->pudoAgregar = $pudoAgregar;
        }
    }

    public function infoeventocalificacionAction() {
        if (!$this->_hasParam('eventocalid') && $this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $evCal = $this->entityManager->find('SGTi\Entity\EventoCalificacion', $this->_getParam('eventocalid'));
        $this->view->selectedCurso = $curso;
        $this->view->selectedEventoCalificacion = $evCal;
    }

    public function infoclaseAction() {
        if (!$this->_hasParam('claseid') && $this->_hasParam('cursoid')) {
            return $this->_redirect('/administracion/adminplanestudio');
        }

        $curso = $this->entityManager->find('SGTi\Entity\Curso', $this->_getParam('cursoid'));
        $clase = $this->entityManager->find('SGTi\Entity\Clase', $this->_getParam('claseid'));
        $this->view->selectedCurso = $curso;
        $this->view->selectedClase = $clase;
    }

    public function cambiarestadoequisitosAction() {
        $alumnoService = Application_Service_Alumno::getInstance();

        $alumnoId = $this->_getParam('personaid');
        $estado = $this->_getParam('estado');

        $alumnoService->updateRequisitos($alumnoId, $estado);

        return $this->_redirect('/administracion/infoalumno/id/' . $alumnoId);
    }

    public function reporteasistenciasAction() {
        if (!$this->_hasParam('idalumno')) {
            return $this->_redirect('/administracion/adminalumnos');
        }

        $alumnoId = $this->_getParam('idalumno');
        $alumno = $this->entityManager->find('SGTi\Entity\Alumno', $alumnoId);
        $alumnoService = Application_Service_Alumno::getInstance();
        $datosAlumno = array();
        $cursoMat = array();
        $clasesMat = array();
        //Cargo datos basicos del alumno para no tener que buscarlos luego en el servicio que genera el html
        $datosAlumno['nombre'] = $alumno->getNombre();
        $datosAlumno['apellido'] = $alumno->getApellido();
        $datosAlumno['plan'] = $alumno->getInscripcion()->getPlanDeEstudio()->getNombre();
        foreach ($alumno->getInscripcion()->getInscripcionesCurso() as $inscripcionCur) {
            //Obtengo el id del curso
            $curId = $inscripcionCur->getCurso()->getId();
            //Obtengo el total de faltas del curso
            $faltas = $this->entityManager->getRepository('SGTi\Entity\Alumno')->findInasistenciasCount($curId, $alumnoId);
            //Obtengo el nombre de la materia a la cual pertenece el curso
            $matNom = $inscripcionCur->getCurso()->getMateria()->getNombre();
            //Asingo a la materia la cantidad de faltas
            $cursoMat[$matNom] = $faltas;
            //Asingo a la materia (curso) la cantidad de clases que hubieron en total
            $clasesMat[$matNom] = sizeof($inscripcionCur->getCurso()->getClases());
        }
        $listado = $alumnoService->listadoAsistencias($cursoMat, $clasesMat, $datosAlumno);
        $this->view->idalumno = $alumnoId;
        echo $listado;
        if ($this->_hasParam('descargar')) {
            require_once('dompdf/dompdf_config.inc.php');
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->pushAutoloader('DOMPDF_autoload', '');
            $dompdf = new DOMPDF();
            $dompdf->load_html($listado);
            $dompdf->render();
            $dompdf->stream("asistencias.pdf");
        }
    }

}

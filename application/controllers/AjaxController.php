<?php

class AjaxController extends Zend_Controller_Action {

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $entityManager;

    public function init() {
        parent::init();

        // como recibo solo requests XmlHTTP por aca, desactivo todo lo referente a las vistas, para mandar
        // solo texto para atras.
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $this->entityManager = Zend_Registry::get('doctrine')->getEntityManager();
    }

    public function preDispatch() {
        parent::preDispatch();

        // don't do anything if we don't come from an ajax request
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return;
        }
    }

    public function getMateriasPlanJsonAction() {
        $planRepository = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio');
        $planId = $this->_getParam('planId');

        $materias = $planRepository->findMateriasPlan($planId);

        if (empty($materias)) {
            return "";
        }

        $json = \SGTi\Helper\Helper::arrayToJson($materias);
        echo $json;
    }

    public function getPeriodosPlanJsonAction() {
        $planRepository = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio');
        $planId = $this->_getParam('planId');

        $periodos = $planRepository->findPeriodosPlan($planId);

        if (empty($periodos)) {
            return "";
        }

        $json = \SGTi\Helper\Helper::arrayToJson($periodos);
        echo $json;
    }

    public function getCursosPeriodoJsonAction() {
        $periodoRepository = $this->entityManager->getRepository('SGTi\Entity\Periodo');
        $planId = $this->_getParam('planId');
        $periodoId = $this->_getParam('periodoId');

        $cursos = $periodoRepository->findCursosPeriodo($planId, $periodoId);

        if (empty($cursos)) {
            return "";
        }

        $json = \SGTi\Helper\Helper::arrayToJson($cursos);
        echo $json;
    }

    public function getAlumnosCursoJsonAction() {
        $cursoRepository = $this->entityManager->getRepository('SGTi\Entity\Curso');
        $cursoId = $this->_getParam('cursoId');
        $examen = $this->_hasParam('examen');

        $alumnos = $cursoRepository->findAlumnos($cursoId, $examen);

        if (empty($alumnos)) {
            return "";
        }

        $json = \SGTi\Helper\Helper::arrayToJson($alumnos);
        echo $json;
    }

    public function getAlumnosPlanJsonAction() {
        $planRepository = $this->entityManager->getRepository('SGTi\Entity\PlanDeEstudio');
        $planId = $this->_getParam('planId');
        
        $alumnos = $planRepository->findAlumnosPlan($planId);
        
        if (empty($alumnos)) {
            return "";
        }
        
        //$json = \SGTi\Helper\Helper::arrayToJson($alumnos, true);
        $json = json_encode($alumnos);
        echo $json;
    }

    public function getNoticiaHtmlAction() {
        $NoticiaRepository = $this->entityManager->getRepository('SGTi\Entity\Noticia');
        $noticiaId = $this->_getParam('noticiaId');
        $noticia = $NoticiaRepository->find($noticiaId);

        $modal = '<div class="modal-header">';
        $modal .= '<a href="#" class="close">x</a>';
        $modal .= '<h1>' . $noticia->getTitulo() . '</h1>';
        $modal .= '</div><div class="modal-body">';
        $modal .= '<p>' . $noticia->getContenido() . '</p>';
        $modal .= '</div><div class="modal-footer">';
        $modal .= '<span class="label notice pull-right">' . $noticia->getUltimaActualizacion()->format('d-m-y') . '</span></div>';

        echo $modal;
    }

}
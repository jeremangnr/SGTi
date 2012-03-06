<?php

class IndexController extends Zend_Controller_Action {

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
        $this->session->main_tab_location = 0;
        $this->entityManager = Zend_Registry::get('doctrine')->getEntityManager();
    }

    public function preDispatch() {
        parent::preDispatch();
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/seguridad/login');
        }
    }

    public function indexAction() {
        $noticias = $this->entityManager->getRepository('SGTi\Entity\Noticia')->findByDate();
        $this->view->noticias = $noticias;

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/tools/paginator.phtml');

        $paginator = Zend_Paginator::factory($noticias);
        $paginator->setItemCountPerPage(3);
        $paginator->setPageRange(8);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;

      
    }

    public function vernoticiaAction() {

        if (!$this->_hasParam('noticiaid')) {
            $this->_redirect('/index');
        }
        
        $noticia = $this->entityManager->find('SGTi\Entity\Noticia', $this->_getParam('noticiaid'));
        $this->view->selectedNoticia = $noticia;
    }

    public function infoAction() {
        $this->session->main_tab_location = 5;
        $noticias = $this->entityManager->getRepository('SGTi\Entity\Noticia')->findAll();

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/tools/paginator.phtml');
        $paginator = Zend_Paginator::factory($noticias);
        $paginator->setItemCountPerPage(6);
        $paginator->setPageRange(5);

        if ($this->_hasParam('page')) {
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }

        $this->view->paginator = $paginator;


        if ($this->_hasParam('noticiaid')) {
            $noticia = $this->entityManager->find('SGTi\Entity\Noticia', $this->_getParam('noticiaid'));
            $this->view->selectedNoticia = $noticia;
        }
    }

}


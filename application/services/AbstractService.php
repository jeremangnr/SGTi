<?php

/**
 * Base class for services, it contains some common resources that need to be available for
 * them to interact with the database abstraction layer.
 **/
abstract class Application_Service_AbstractService {
    /**
     *
     * The Doctrine Container. Handles all interaction with the database and the entities.
     * 
     * @var \Bisna\Doctrine\Container
     */
    protected $doctrineContainer;
    /**
     *
     * For conveniance the Entity Manager is put separatedly.
     * 
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $entityManager;
    
    public function __initResources() {
        $this->doctrineContainer = Zend_Registry::get('doctrine');
        $this->entityManager = $this->doctrineContainer->getEntityManager();
        
    }
}

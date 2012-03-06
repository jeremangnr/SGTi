<?php

class ModelTestCase extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var \Bisna\Application\Container\DoctrineContainer
     */
    protected $doctrineContainer;

    public function setUp() {
        // Create application, bootstrap, and run
        global $application;
        
        $application->bootstrap();

        $this->doctrineContainer = Zend_Registry::get('doctrine');
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

}

<?php
// set include path to Zend (and other external libs) location before ANYTHING
set_include_path('../vendor' . PATH_SEPARATOR . get_include_path());

/* ESTAS CONFIGS DEBEN IR EN EL PHP.INI DEL SERVER */

// para que no haya lio con los timestamp en la BD y otras cosas locas
date_default_timezone_set('America/Montevideo');
// para el upload de material (no se puede hacer con ini_set)
//ini_set('upload_max_filesize', '10MB');

/* ESTAS CONFIGS DEBEN IR EN EL PHP.INI DEL SERVER */

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();
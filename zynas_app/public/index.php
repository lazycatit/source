<?php
define ( 'APPLICATION_ENV', getenv ( 'APPLICATION_ENV' ) );
define ( 'APPLICATION_PATH', getenv ( 'APPLICATION_PATH' ) );
define ( 'FRAMEWORK_PATH', getenv ( 'FRAMEWORK_PATH' ) );
define ( 'MODULE_PATH', APPLICATION_PATH . '/modules' );

define ( 'APP_BASE', APPLICATION_PATH . '/../' );
define ( 'APP_HOME', APPLICATION_PATH . '/' );

set_include_path ( implode ( PATH_SEPARATOR, array (
		FRAMEWORK_PATH . '/library',
		get_include_path () 
) ) );

require_once 'Zend/Application.php';
$application = new Zend_Application ( APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini' );
$application->bootstrap ()->run ();

?>
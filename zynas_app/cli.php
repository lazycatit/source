#!/usr/bin/php
<?php

date_default_timezone_set('Asia/Tokyo');

define('APPLICATION_ENV', 'development');
define('APPLICATION_PATH', '/var/www/test_ltv/dev-ltv/application');
define('FRAMEWORK_PATH', '/var/www/test_ltv/framework');
define('MODULE_PATH', APPLICATION_PATH . '/modules');

define('IS_CLI', true);

set_include_path(implode(PATH_SEPARATOR, array(
FRAMEWORK_PATH . '/library',
get_include_path()))
);

require_once 'Zend/Application.php';
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap()->run();

?>

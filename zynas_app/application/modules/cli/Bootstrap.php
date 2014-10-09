<?php

class Cli_Bootstrap extends ModuleBootstrap {

    protected function _initCli() {
        if (defined('IS_CLI') && IS_CLI) {
            Zend_Controller_Front::getInstance()->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array('module' => 'cli')));
        }
    }

}

?>

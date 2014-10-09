<?php

class Cli_ErrorController extends Controller {

    public function errorAction() {
        $errors = $this->_getParam('error_handler');
        echo $errors->exception->getMessage() . PHP_EOL;
        die();
    }

}
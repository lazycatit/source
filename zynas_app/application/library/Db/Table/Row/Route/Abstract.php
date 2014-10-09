<?php

abstract class Db_Table_Row_Route_Abstract extends Db_Table_Row {

    protected $_defaultsController;
    protected $_defaultsAction;

    public function getDefaultsController() {
        return $this->_defaultsController;
    }

    public function getDefaultsAction() {
        return $this->_defaultsAction;
    }

    public function setDefaultsController($controller) {
        $this->_defaultsController = $controller;
    }

    public function setDefaultsAction($action) {
        $this->_defaultsAction = $action;
    }

}

?>

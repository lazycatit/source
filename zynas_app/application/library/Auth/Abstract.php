<?php

abstract class Auth_Abstract {

    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    protected function setData($obj) {
        $this->_auth->getStorage()->write($obj);
    }

    public function getData() {
        return $this->_auth->getIdentity();
    }

    public function clear() {
        $this->_auth->clearIdentity();
    }

    public function isLogged() {
        return $this->_auth->hasIdentity();
    }


}
<?php

class Zynas_SessionManager {

    const NSPACE = 'SM';

    const CONNECTOR = '_';

    protected $moduleName;
    protected $controllerName;

    protected $session;

    public function __construct($moduleName, $controllerName) {
        $this->moduleName = $moduleName;
        $this->controllerName = $controllerName;
        $this->session = Zynas_Session_Namespace::factory(self::NSPACE);
        $this->_prepare();
    }

    protected function _prepare() {
        if (!isset($this->session->scope)) $this->session->scope = array();
        if (!isset($this->session->data)) $this->session->data = array();
    }

    public function setLoginScope($key, $data) {
        $this->_set($key, $data, Zynas_SessionScope::SCOPE_LOGIN);
    }
    public function setModuleScope($key, $data) {
        $this->_set($key, $data, Zynas_SessionScope::SCOPE_MODULE);
    }
    public function setControllerScope($key, $data) {
        $this->_set($key, $data, Zynas_SessionScope::SCOPE_CONTROLLER);
    }
    public function setOnceScope($key, $data) {
        $this->_set($key, $data, Zynas_SessionScope::SCOPE_ONCE);
    }
    protected function _set($key, $data, $scope) {
        $uniqueKey = $this->createUniqueKey($key);
        $this->session->scope[$uniqueKey] = new Zynas_SessionScope($this->moduleName, $this->controllerName, $scope);
        $this->session->data[$uniqueKey] = $data;
    }

    public function getData($key) {
        $uniqueKey = $this->createUniqueKey($key);
        return $this->_get($uniqueKey);
    }
    protected function _get($uniqueKey) {
        $data = isset($this->session->data[$uniqueKey]) ? $this->session->data[$uniqueKey] : null;
        $scope = isset($this->session->scope[$uniqueKey]) ? $this->session->scope[$uniqueKey] : null;
        if (is_null($scope) || (strcmp($scope->scope, Zynas_SessionScope::SCOPE_ONCE) === 0)) {
            $this->_remove($uniqueKey);
        }
        return $data;
    }

    public function removeData($key) {
        $uniqueKey = $this->createUniqueKey($key);
        $this->_remove($uniqueKey);
    }
    protected function _remove($uniqueKey) {
        $this->session->scope[$uniqueKey] = null;
        $this->session->data[$uniqueKey] = null;
        unset($this->session->scope[$uniqueKey]);
        unset($this->session->data[$uniqueKey]);
    }

    public function clear() {
        foreach ($this->session->data as $uniqueKey => $data) {
            $scope = $this->session->scope[$uniqueKey];
            switch ($scope->scope) {
                case Zynas_SessionScope::SCOPE_MODULE:
                    if (strcmp($this->moduleName, $scope->moduleName) !== 0) {
                        $this->_remove($uniqueKey);
                    }
                    break;
                case Zynas_SessionScope::SCOPE_CONTROLLER:
                    if (strcmp($this->moduleName, $scope->moduleName) !== 0
                        || strcmp($this->controllerName, $scope->controllerName) !== 0) {
                        $this->_remove($uniqueKey);
                    }
                    break;
                case Zynas_SessionScope::SCOPE_LOGIN:
                case Zynas_SessionScope::SCOPE_ONCE:
                    break;
                default:
                        $this->_remove($uniqueKey);
                    break;
            }
        }
    }

    public static function destory() {
        $session = Zynas_Session_Namespace::factory(self::NSPACE);
        $session->unsetAll();
    }

    protected function createUniqueKey($key) {
        return $this->moduleName . self::CONNECTOR . $this->controllerName . self::CONNECTOR . $key;
    }
}
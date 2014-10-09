<?php

class Zynas_SessionScope {

    const SCOPE_LOGIN = 'scope_login';
    const SCOPE_MODULE = 'scope_module';
    const SCOPE_CONTROLLER = 'scope_controller';
    const SCOPE_ONCE = 'scope_once';

    private $scope;
    private $moduleName;
    private $controllerName;

    public function __construct($moduleName, $controllerName, $scope) {
        $this->scope = $scope;
        $this->moduleName = $moduleName;
        $this->controllerName = $controllerName;
    }

    public function __set($name, $data) {
        $this->{$name} = $data;
    }

    public function __get($name) {
        return  $this->{$name};
    }

}
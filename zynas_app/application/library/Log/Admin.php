<?php
class Log_Admin {

    public static function convertControllerName($controllerName){
        $controllerName = str_replace("-", "_", $controllerName);
        $convertName = isset(Zynas_Registry::getConfig()->log->controller->{$controllerName}) ? Zynas_Registry::getConfig()->log->controller->{$controllerName} : null;
        if (is_null($convertName)) {
            return $controllerName;
        }
        return $convertName;
    }

    public static function convertActionName($controllerName, $actionName){
        $controllerName = str_replace("-", "_", $controllerName);
        $actionName = str_replace("-", "_", $actionName);
        $convertName = isset(Zynas_Registry::getConfig()->log->action->{$controllerName}->{$actionName}) ? Zynas_Registry::getConfig()->log->action->{$controllerName}->{$actionName} : null;
        if (is_null($convertName)) {
            return $actionName;
        }
        return $convertName;
    }

    public static function isLogAction($controllerName, $actionName){
        $controllerName = str_replace("-", "_", $controllerName);
        $actionName = str_replace("-", "_", $actionName);
        $convertName = isset(Zynas_Registry::getConfig()->log->action->{$controllerName}->{$actionName}) ? Zynas_Registry::getConfig()->log->action->{$controllerName}->{$actionName} : null;
        if (is_null($convertName)) {
            return false;
        }
        return true;
    }

}
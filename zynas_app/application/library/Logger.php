<?php

class Log {

    public static function debugLog($error, array $requestParams = array()) {
        self::getInstance()->debug(self::createModuleName($requestParams) . self::getErrorMessage($error));
    }

    public static function infoLog($error, array $requestParams = array()) {
        self::getInstance()->info(self::createModuleName($requestParams) . self::getErrorMessage($error));
    }

    public static function errorLog($error, array $requestParams = array()) {
        self::getInstance()->error(self::createModuleName($requestParams) . self::getErrorMessage($error));
    }


    protected static function getErrorMessage($error) {
        $message = '';
        if ($error instanceof Exception) {
            $message = $error->getMessage();
            if (empty($message)) {
                $message = $error->getFile() . ' ' . $error->getLine();
            }
        } else {
            $message = $error;
        }
        return $message;
    }

    protected static function createModuleName(array $requestParams) {
        $moduleName = '';
        if (array_key_exists('module', $requestParams) && array_key_exists('controller', $requestParams) && array_key_exists('action', $requestParams)) {
            $moduleName = '[' . $requestParams['module'] . '/' . $requestParams['controller'] . '/' . $requestParams['action'] . '] ';
        }
        return $moduleName;
    }

    protected static function getInstance() {
        return Logger::getLogger('default');
    }

}

?>
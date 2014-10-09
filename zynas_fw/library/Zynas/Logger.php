<?php

class Zynas_Logger {

    private static $_instances = array();

    public static function infoLog($error, array $requestParams = array()) {
        self::getInstance(Zend_Log::INFO)->info(self::createModuleName($requestParams) . self::getErrorMessage($error));
    }

    public static function errorLog($error, array $requestParams = array()) {
        self::getInstance(Zend_Log::ERR)->err(self::createModuleName($requestParams) . self::getErrorMessage($error));
    }

    public static function infoLogCli($message, array $requestParams = array()) {
        self::getInstance(Zend_Log::INFO, '_cli')->info(self::createModuleName($requestParams) . $message);
    }

    public static function errorLogCli($message, array $requestParams = array()) {
        self::getInstance(Zend_Log::ERR, '_cli')->err(self::createModuleName($requestParams) . $message);
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

    protected static function getInstance($logType, $postfix='') {
        $key = self::getPrefix($logType) . $postfix;
        if (!array_key_exists($key , self::$_instances)) {
            self::$_instances[$key] = new Zend_Log(new Zend_Log_Writer_Stream(self::getLogPath($logType, $postfix)));
        }
        return self::$_instances[$key];
    }

    protected static function getPrefix($logType){
        $prefix = '';
        switch ($logType) {
            case Zend_Log::EMERG:
            case Zend_Log::ALERT:
            case Zend_Log::CRIT:
            case Zend_Log::ERR:
            case Zend_Log::WARN:
            case Zend_Log::NOTICE:
                $prefix = 'error_';
                break;
            case Zend_Log::INFO:
                $prefix = 'info_';
                break;
            case Zend_Log::DEBUG:
                $prefix = 'debug_';
                break;
            default:
                $prefix = 'etc_';
        }
        return $prefix;
    }

    protected static function getLogPath($logType, $postfix=''){
        return implode('/' , array(LOG_PATH, self::getPrefix($logType) . date('Ymd') . $postfix . '.log'));
    }

}

?>
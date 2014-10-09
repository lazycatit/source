<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected $_moduleName;

    protected function _initAutoload() {
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
        require_once(FRAMEWORK_PATH . '/etc/func.php');
        set_include_path(implode(PATH_SEPARATOR, array(
        APPLICATION_PATH . '/library/ParameterSheet',
        APPLICATION_PATH . '/library/log4php',
        APPLICATION_PATH . '/library/log4php/renderers',
        APPLICATION_PATH . '/library/log4php/appenders',
        APPLICATION_PATH . '/library/log4php/configurators',
        APPLICATION_PATH . '/library/log4php/filters',
        APPLICATION_PATH . '/library/log4php/helpers',
        APPLICATION_PATH . '/library/log4php/layouts',
        APPLICATION_PATH . '/library/log4php/pattern',
        APPLICATION_PATH . '/library/log4php/xml',
        get_include_path()
        )
        ));
        require_once(APPLICATION_PATH.'/library/ParameterSheet/CreatePublishHist.php');
        require_once(APPLICATION_PATH.'/library/ParameterSheet/IssueRequest.php');
        require_once(APPLICATION_PATH.'/library/ParameterSheet/IssueProduct.php');
    }

    protected function _initView() {
        $view = new Zend_View();
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        return $view;
    }

    protected function _initPlugin() {
        Zend_Controller_Front::getInstance()->registerPlugin(new Controller_Plugin_Application());
    }

    protected function _initPath() {
        $configPath = APPLICATION_PATH . '/configs/path.ini';
        if(file_exists($configPath)) {
            $config = new Zend_Config_Ini($configPath);
            $array = $config->toArray();
            foreach ($array as $key => $value) define($key, $value);
        }
    }

    protected function _initMessage() {
        $configPath = APPLICATION_PATH . '/configs/message.ini';
        if(file_exists($configPath)) {
            $config = new Zend_Config_Ini($configPath);
            $array = $config->toArray();
            foreach ($array as $key => $value) define($key, $value);
        }
    }

    protected function _initDatabase() {
        $config = $this->getOptions();
        Zynas_Db_Table::setDefaultAdapter(Zend_Db::factory($config['resources']['db']['adapter'], $config['resources']['db']['params']));
        Zynas_Db_Table::setPrefix($config['resources']['db']['table']['prefix']);
    }

    protected function _initSession() {
        if (!(defined('IS_CLI') && IS_CLI)) {
            $resources = $this->getApplication()->getOption('resources');
            Zend_Session::setOptions($resources['session']['options']);
            $sessDb = new Zend_Session_SaveHandler_DbTable($resources['session']['database']);
            Zend_Session::setSaveHandler($sessDb);
            Zend_Session::start();
        }
    }

    protected function _initTimezone() {
        date_default_timezone_set('Asia/Tokyo');
    }

    protected function _initConfig() {
        Zynas_Registry::setConfig(new Zend_Config($this->getApplication()->getOptions(), true));
    }

    protected function _initMail() {
        $configPath = APPLICATION_PATH . '/configs/mail.ini';
        $config = new Zend_Config_Ini($configPath, APPLICATION_ENV, array('allowModifications' => true));
        Zynas_Registry::setConfig(new Zend_Config($this->fArray_merge(Zynas_Registry::getConfig()->toArray(), $config->toArray())));
    }

    protected function _initCli() {
        if (defined('IS_CLI') && IS_CLI) {
            $front = Zend_Controller_Front::getInstance();
            $front->setRequest(Zynas_Controller_Request_Cli::create());
            $front->setRouter(new Zynas_Controller_Router_Cli());
            $front->setResponse(new Zynas_Controller_Response_Cli());
        }
    }

    protected function _initLog() {        
        require_once(APPLICATION_PATH.'/library/log4php/Logger.php');
        require_once (APPLICATION_PATH.'/configs/log4config.php');
        require_once ( APPLICATION_PATH.'/library/Logger.php');
    }

    function fArray_merge ($aOld, $aNew) {
        if(is_array($aOld)) {
            if(is_array($aNew)) {
                foreach($aNew as $sKey => $mValue) {
                    if(isset($aOld[$sKey]) && is_array($mValue) && is_array($aOld[$sKey])) {
                        $aOld[$sKey] = $this->fArray_merge($aOld[$sKey], $mValue);
                    }
                    else{
                        $aOld[$sKey] = $mValue;
                    }
                }
            }
        }
        elseif(!is_array($aOld) && (strlen($aOld) == 0 || $aOld == 0)) {
            $aOld = $aNew;
        }

        return($aOld);
    }

}

?>
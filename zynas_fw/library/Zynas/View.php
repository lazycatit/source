<?php

class Zynas_View extends Zend_View {

    protected static $_instance;

    public static function factory($suffix, $scriptPath, $helperPath) {
        $class = 'Zynas_View_' . $suffix;
        if (!class_exists($class)) throw new Zynas_View_Exception('No Zynas_View class found: ' . $class);
        if (!self::$_instance instanceof Zynas_View) {
            self::$_instance = new $class();
            self::$_instance->setScriptPath($scriptPath);
            foreach ((array) $helperPath as $p) self::$_instance->addHelperPath($p[0], $p[1]);
            self::$_instance->addHelperPath(Zynas_PATH_FW_LIBRARY . 'Zynas/View/Helper/', 'Zynas_View_Helper_');
        }
        return self::$_instance;
    }

    public function render($name) {
        error_reporting(E_ALL ^ E_NOTICE);
        return parent::render($name);
    }

}

?>
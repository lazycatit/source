<?php

class Zynas_Controller_Front extends Zend_Controller_Front {
    /**
     *
     * @param unknown_type $pathModule
     * @return Zend_Controller_Front
     */
    public static function create($pathModule) {
        $front = Zynas_Controller_Front::getInstance();
        $front->setModuleControllerDirectoryName('controller');
        $front->addModuleDirectory($pathModule);
        $front->setParam('noViewRenderer', true);
        return $front;
    }
}

?>
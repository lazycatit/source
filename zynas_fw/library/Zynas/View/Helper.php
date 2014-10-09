<?php

class Zynas_View_Helper {

    private $_view;

    public function setView(Zend_View_Interface $view) {
        $this->_view = $view;
    }

    public function getView() {
        return $this->_view;
    }

}

?>
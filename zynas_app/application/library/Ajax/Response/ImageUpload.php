<?php

class Ajax_Response_ImageUpload {

    private $_responseData = array(
        'status' => false,
        'error' => null,
        'filename' => null,
        'element_id' => null
    );

    public function __construct($elementId = null) {
        $this->_responseData['element_id'] = $elementId;
    }

    public function setStatus($status) {
        $this->_responseData['status'] = (bool) $status;
    }

    public function setError($error) {
        $this->_responseData['error'] = $error;
    }

    public function setFilename($filename) {
        $this->_responseData['filename'] = $filename;
    }

    public function send() {
        // !!! side: Firefoxではapplication/jsonコンテントタイプが認識されないようなので。
        //header('Content-Type: application/json; charset=UTF-8');
        echo Zend_Json::encode($this->_responseData);
        die();
    }

}

?>
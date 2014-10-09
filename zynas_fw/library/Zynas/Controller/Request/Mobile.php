<?php

class Zynas_Controller_Request_Mobile extends Zend_Controller_Request_Http {

    public function __construct($uri = null) {
        $_GET = $this->_toUtf8($_GET);
        $_POST = $this->_toUtf8($_POST);
        parent::__construct($uri);
    }

    private function _toUtf8($var) {
        if (is_array($var)) {
            foreach ($var as $k => $v) $var[$k] = $this->_toUtf8($v);
            return $var;
        }
        else {
            return mb_convert_kana(mb_convert_encoding($var, 'UTF-8', 'SJIS-win'), 'KV');
        }
    }

    public function isDocomo() {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        return strpos($ua, 'DoCoMo') === false ? false : true;
    }

    public function isAu() {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        return strpos($ua, 'UP.Browser') === false ? false : true;
    }

    public function isSoftbank() {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        return (strpos($ua, 'J-PHONE') === false && strpos($ua, 'Vodafone') === false && strpos($ua, 'MOT-') === false && strpos($ua, 'SoftBank') === false) ? false : true;
    }

}

?>
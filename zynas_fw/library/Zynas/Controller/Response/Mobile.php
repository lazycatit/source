<?php

class Zynas_Controller_Response_Mobile extends Zend_Controller_Response_Http {
    public function outputBody() {
        $content = implode('', $this->_body);
        $content = str_replace('action=""', 'action="' . $_SERVER['REQUEST_URI'] . '"', $content);
        $content = mb_convert_kana($content, 'k', 'UTF-8');
        $content = mb_convert_encoding($content, 'SJIS-win', 'UTF-8');
        echo $content;
    }
}

?>
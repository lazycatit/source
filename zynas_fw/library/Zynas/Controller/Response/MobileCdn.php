<?php

class Zynas_Controller_Response_MobileCdn extends Zend_Controller_Response_Http {

    private $_fqdnHttp;
    private $_fqdnHttps;

    public function outputBody() {
        $content = implode('', $this->_body);
        $content = str_replace('action=""', 'action="' . $_SERVER['REQUEST_URI'] . '"', $content);

        if (!isset($_SERVER['HTTPS']) &&
            !isset($_SERVER['SSL_PROTOCOL']) &&
            !is_numeric(strpos($_SERVER['SERVER_PROTOCOL'], 'https'))
        ) {
            $cdn = 'http://' . $this->_fqdnHttp . '/';
            $content = str_replace(' src="/resources/', ' src="' . $cdn . 'resources/', $content);
            $content = str_replace(' href="/resources/', ' href="' . $cdn . 'resources/', $content);
        }

        $content = mb_convert_kana($content, 'k', 'UTF-8');
        $content = mb_convert_encoding($content, 'SJIS-win', 'UTF-8');
        echo $content;
    }

    public function setFqdns($fqdnHttp, $fqdnHttps = null) {
        $this->_fqdnHttp = $fqdnHttp;
        $this->_fqdnHttps = !is_null($fqdnHttps) ? $fqdnHttps : $fqdnHttp;
    }

}

?>
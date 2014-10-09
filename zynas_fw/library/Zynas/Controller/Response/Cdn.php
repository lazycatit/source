<?php

class Zynas_Controller_Response_Cdn extends Zend_Controller_Response_Http {

    private $_fqdnHttp;
    private $_fqdnHttps;

    public function outputBody() {
        $cdn = (isset($_SERVER['HTTPS']) ? 'https://' . $this->_fqdnHttps : 'http://' . $this->_fqdnHttp) . '/';
        $content = implode('', $this->_body);
        $content = str_replace(' src="/resource/', ' src="' . $cdn . 'resource/', $content);
        $content = str_replace(' href="/resource/', ' href="' . $cdn . 'resource/', $content);
        echo $content;
    }

    public function setFqdns($fqdnHttp, $fqdnHttps = null) {
        $this->_fqdnHttp = $fqdnHttp;
        $this->_fqdnHttps = !is_null($fqdnHttps) ? $fqdnHttps : $fqdnHttp;
    }

}

?>
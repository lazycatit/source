<?php

class XmlResponse_Abstract {

    protected $_dom = null;
    protected $_root = null;

    public function __construct($root = 'root') {
        $this->_dom = new DomDocument('1.0', 'UTF-8');
        $this->_dom->formatOutput = true;
        $this->_root = $this->_dom->appendChild($this->_dom->createElement($root));
    }

    public function addRoot($element) {
        $this->_root->appendChild($element);
    }

    public function render() {
        header("Content-Type: text/xml");
        return $this->_dom->saveXML();
    }

    public function createElement($name, $value = null) {
        return $this->_dom->createElement($name, $value);
    }

    public function createTextNode($text) {
        return $this->_dom->createTextNode($text);
    }

}

?>
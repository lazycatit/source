<?php

class Zynas_Csv_Writer_Stream extends Zynas_Csv_Writer_Abstract {

    private $_filename;

    public function begin() {
        if (is_null($this->_filename)) throw new Zynas_Csv_Writer_Exception('Filename is not set.');
        header('Cache-Control: public');
        header('Pragma: public');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $this->getFilename() . ';');
        if (count($this->getHeaders()) > 0) $this->putRecord($this->getHeaders());
    }

    public function write($ary) {
        echo $this->getEnclosure() . implode($this->getEnclosure() . $this->getDelimiterField() . $this->getEnclosure(), $ary) . $this->getEnclosure() . $this->getDelimiterRecord();
    }

    public function send() {
        die();
    }

    public function getFilename() {
        return $this->_filename;
    }

    public function setFilename($str) {
        $this->_filename = $str;
    }

}

?>
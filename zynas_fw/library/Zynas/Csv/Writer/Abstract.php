<?php

abstract class Zynas_Csv_Writer_Abstract {

    const ENCODING_UTF8 = 'UTF-8';
    const ENCODING_EUC = 'EUC-JP';
    const ENCODING_SJIS = 'Shift_JIS';
    const ENCODING_SJISW  = 'SJIS-win';

    private $_headers = array();
    private $_encodingFrom = 'UTF-8';
    private $_encodingTo = 'SJIS-win';
    private $_delimiterField = ',';
    private $_delimiterRecord = "\r\n";
    private $_enclosure = '"';
    private $_filling = '';
    private $_numColumns = 0;

    abstract public function begin();
    abstract public function write($ary);
    abstract public function send();

    public function putRecord($ary) {
        $numElements = count($ary);
        if ($numElements < $this->getNumColumns()) {
            $iTo = $this->getNumColumns() - $numElements;
            for ($i = 0; $i < $iTo; $i++) $ary[] = $this->getFilling();
        }
        else if ($numElements > $this->getNumColumns()) {
            $ary = array_slice($ary, 0, $numElements - $this->getNumColumns());
        }
        foreach ($ary as $k => $v) {
            if ($this->_encodingTo != $this->_encodingFrom) $v = mb_convert_encoding($v, $this->_encodingTo, $this->_encodingFrom);
            $ary[$k] = str_replace($this->getEnclosure(), $this->getEnclosure() . $this->getEnclosure(), $v);
        }
        $this->write($ary);
    }

    public function putRecords($ary) {
        foreach ((array) $ary as $v) $this->putRecord($v);
    }

    public function getDelimiterField() {
        return $this->_delimiterField;
    }

    public function setDelimiterField($str) {
        $this->_delimiterField = $str;
    }

    public function getDelimiterRecord() {
        return $this->_delimiterRecord;
    }

    public function setDelimiterRecord($str) {
        $this->_delimiterRecord = $str;
    }

    public function getEnclosure() {
        return $this->_enclosure;
    }

    public function setEnclosure($str) {
        $this->_enclosure = $str;
    }

    public function getEncodingFrom() {
        return $this->_encodingFrom;
    }

    public function setEncodingFrom($str) {
        $this->_encodingFrom = $str;
    }

    public function getEncodingTo() {
        return $this->_encodingTo;
    }

    public function setEncodingTo($str) {
        $this->_encodingTo = $str;
    }

    public function getFilling() {
        return $this->_filling;
    }

    public function setFilling($var) {
        $this->_filling = $var;
    }

    public function getHeaders() {
        return $this->_headers;
    }

    public function setHeaders($ary) {
        $this->_headers = $ary;
        $this->_numColumns = count($ary);
    }

    public function getNumColumns() {
        return $this->_numColumns;
    }

    public function setNumColumns($int) {
        $this->_numColumns = $int;
    }


}

?>
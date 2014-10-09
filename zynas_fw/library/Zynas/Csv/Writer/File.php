<?php

class Zynas_Csv_Writer_File extends Zynas_Csv_Writer_Abstract {

    private $_pathFile;
    private $_fp;

    public function __destruct() {
        if (is_resource($this->_fp)) fclose($this->_fp);
    }

    public function begin() {
        if (is_null($this->_pathFile)) throw new Zynas_Csv_Writer_Exception('CSV file path is not set.');
        if (file_exists($this->_pathFile)) {
            if (!is_writable($this->_pathFile)) throw new Zynas_Csv_Writer_Exception('CSV file is not writable: ' . $this->_pathFile);
        }
        else {
            $dir = dirname($this->_pathFile);
            if (!is_dir($dir)) throw new Zynas_Csv_Writer_Exception('No such directory: ' . $dir);
            if (!is_writable($dir)) throw new Zynas_Csv_Writer_Exception('Permission denied to write to the directory: ' . $dir);
        }
        $this->_fp = fopen($this->_pathFile, 'w');
        if (count($this->getHeaders()) > 0) $this->putRecord($this->getHeaders());
    }

    public function write($ary) {
        fwrite($this->_fp, $this->getEnclosure() . implode($this->getEnclosure() . $this->getDelimiterField() . $this->getEnclosure(), $ary) . $this->getEnclosure() . $this->getDelimiterRecord());
    }

    public function send() {
        fclose($this->_fp);
    }

    public function getPathFile() {
        return $this->_pathFile;
    }

    public function setPathFile($path) {
        $this->_pathFile = $path;
    }

}

?>
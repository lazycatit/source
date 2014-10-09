<?php

class Zynas_Image {

    private $_ext;
    private $_path;
    private $_imgInfo;
    private $_webImageMimes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);

    public function __construct($path) {
        $this->_path = $path;
        $this->_imgInfo = getimagesize($path);
        if (!$this->_imgInfo) throw new Zynas_Exception('Not an image: ' . $path);
    }

    public function __get($name) {
        switch ($name) {
            // side: 現在、ウェブ画像にしか対応していません。
            case 'ext':
                if (!$this->_ext) {
                    switch ($this->_imgInfo[2]) {
                        case IMAGETYPE_JPEG: $this->_ext = 'jpg'; break;
                        case IMAGETYPE_PNG: $this->_ext = 'png'; break;
                        case IMAGETYPE_GIF: $this->_ext = 'gif'; break;
                    }
                }
                return $this->_ext;
                break;
        }
    }

    public function isWebImage() {
        return in_array($this->_imgInfo[2], $this->_webImageMimes);
    }

    public static function isWebImageMime($mime) {
        if ($mime == 'image/jpg' || $mime == 'image/jpeg' || $mime == 'image/pjpeg' || $mime == 'image/png' || $mime == 'image/x-png' || $mime == 'image/gif') {
            return true;
        }
        else {
            return false;
        }
    }

    public static function mimeToExt($mime) {
        switch ($mime) {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                return 'jpg';
                break;
            case 'image/png':
            case 'image/x-png':
                return 'png';
                break;
            case 'image/gif':
                return 'gif';
                break;
            default:
                return null;
        }
    }
}

?>
<?php

class Validate_AuthProductImage extends Zend_Validate_Abstract {

    const NO_MATCH = 'notMatch';

    protected $_messageTemplates = array(
    self::NO_MATCH => '画像の文字と一致していません。'
    );

    public function isValid($value) {
        if (!View_Helper_AuthProductImage::isValid($value)) {
            $this->_error(self::NO_MATCH);
            return false;
        }
        return true;
    }
}
?>
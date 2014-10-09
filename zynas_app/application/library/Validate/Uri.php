<?php

class Validate_Uri extends Zend_Validate_Abstract {

    const INVALID = 'uriInvalid';

    protected $_messageTemplates = array(
    self::INVALID => 'URIの形式が正しくありません。'
    );

    public function isValid($value) {
        if (!(preg_match('/^(http|HTTP)(s|S)?:\/\/+[A-Za-z0-9]/',$value))) {
            $this->_error(self::INVALID);
            return false;
        }
        return true;
    }

}

?>
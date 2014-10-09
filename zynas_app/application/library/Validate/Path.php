<?php

class Validate_Path extends Zend_Validate_Abstract {

    const INVALID = 'pathInvalid';

    protected $_messageTemplates = array(
    self::INVALID => '形式が正しくありません。'
    );

    public function isValid($value) {
        if (!(preg_match('/^[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+$/',$value))) {
            $this->_error(self::INVALID);
            return false;
        }
        return true;
    }

}

?>
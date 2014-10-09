<?php

class Validate_Retype extends Zend_Validate_Abstract {

    const NO_MATCH = 'notMatch';

    protected $_messageTemplates = array(
    self::NO_MATCH => '一致していません。'
    );

    public function isValid($value) {
        if (!is_array($value) || count($value) != 2) {
            throw new Zend_Validate_Exception('Value is not 2 parammeters array.');
        }
        $input = array_shift($value);
        $comp = array_shift($value);
        if ($input != $comp) {
            $this->_error(self::NO_MATCH);
            return false;
        }
        return true;
    }
}
?>
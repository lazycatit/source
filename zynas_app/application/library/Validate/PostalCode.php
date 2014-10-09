<?php

class Validate_PostalCode extends Zend_Validate_Abstract {

    const NOT_POSTAL_FORMAT = 'alnumusNotPostalFormat';
    const STRING_EMPTY = 'alnumusStringEmpty';
    
    protected $_messageTemplates = array(
    self::NOT_POSTAL_FORMAT => E035V,
    self::STRING_EMPTY => E072V
    );

    public function isValid($value) {
        if ('' === $value) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }
        if (!preg_match('/^(\d{3}\-\d{4}|\d{7})$/', $value)) {
            $this->_error(self::NOT_POSTAL_FORMAT);
            return false;
        }
        return true;
    }

}

?>
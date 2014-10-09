<?php

class Validate_Alnumus extends Zend_Validate_Abstract {

    const NOT_ALNUMUS = 'alnumusNotAlnumus';
    const STRING_EMPTY = 'alnumusStringEmpty';

    protected $_messageTemplates = array(
    self::NOT_ALNUMUS => E007V,
    self::STRING_EMPTY => E072V
    );

    public function isValid($value) {
        if ('' === $value) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }
        if (preg_match('/[^a-zA-Z0-9]/', $value)) {
            $this->_error(self::NOT_ALNUMUS);
            return false;
        }
        return true;
    }

}

?>
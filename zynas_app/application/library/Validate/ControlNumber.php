<?php

class Validate_ControlNumber extends Zend_Validate_Abstract {

    const NOT_CONTROLNUMBER = 'alnumusNotAlnumus';
    const STRING_TOO_LONG = 'alnumusStringTooLong';

    protected $_messageTemplates = array(
    self::NOT_CONTROLNUMBER => E060V,
    self::STRING_TOO_LONG => E069V
    );

    public function isValid($value) {
        if(!preg_match('/^A([0-9]{2})(0[1-9]{1}|1[0-2]{1})_([0-9]{4})$/', $value)) {
            $this->_error(self::NOT_CONTROLNUMBER);
            return false;
        }
        return true;
    }

}

?>
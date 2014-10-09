<?php

class Validate_EmailAddress extends Zynas_Validate_EmailAddress {

	
    const NOT_EMAIL = 'notEmail';

    protected $_messageTemplates = array(
    self::NOT_EMAIL => E060V
    );

    public function isValid($value) {    	
        $regexp = '/^[A-z0-9\+\/\._\"-]+@[A-z0-9][A-z0-9-]*(\.[A-z0-9_-]+)*\.([A-z]{2,6})$/';
        if (!preg_match($regexp, $value)) {
            $this->_error(self::NOT_EMAIL);
            return false;
        }
        return true;
    }

}
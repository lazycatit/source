<?php

class Validate_RetypePassword extends Zend_Validate_Abstract {

    const INVALID = 'invalid';

    protected $_messageTemplates = array(
    self::INVALID => 'パスワードが一致しません。'
    );

    public function isValid($value) {
        if($_POST['password'] != $value) {
            $this->_error(self::INVALID);
            return false;
        }

        return true;
    }

}

?>
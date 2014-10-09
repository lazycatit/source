<?php

class Validate_Birthday extends Zend_Validate_Abstract {

    const INVALID = 'invalid';

    protected $_messageTemplates = array(
    self::INVALID => '正しい日付を入力してください。'
    );

    public function isValid($value) {
        if (!is_array($value) || count($value) != 3) {
            throw new Zend_Validate_Exception('Value is not 3 parammeters array.');
        }
        $input = array_shift($value) . '-' . sprintf("%02d", array_shift($value)) . '-' . sprintf("%02d", array_shift($value));
        $validator = new Zend_Validate_Date();
        if (!$validator->isValid($input)) {
            $this->_error(self::INVALID);
            return false;
        }
        return true;
    }
}
?>
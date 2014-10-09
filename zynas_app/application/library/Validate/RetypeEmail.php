<?php

class Validate_RetypeEmail extends Zend_Validate_Abstract {

    const IS_EMPTY = 'isEmpty';
    const NO_MATCH = 'notMatch';

    protected $_messageTemplates = array(
    self::IS_EMPTY => '入力必須項目です。',
    self::NO_MATCH => 'メールアドレスが一致していません。'
    );

    public function isValid($value) {
        if (!is_array($value) || count($value) != 3) {
            throw new Zend_Validate_Exception('Value is not 3 parammeters array.');
        }
        $input = array_shift($value) . '@' . array_shift($value);
        if ($input == '@') {
            $this->_error(self::IS_EMPTY);
            return false;
        }
        $comp = array_shift($value);
        if ($input != $comp) {
            $this->_error(self::NO_MATCH);
            return false;
        }
        return true;
    }
}
?>
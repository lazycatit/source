<?php

class Validate_ZenkakuKana extends Zend_Validate_Abstract {

    const NO_MATCH = 'notMatch';

    protected $_messageTemplates = array(
    self::NO_MATCH => 'ひらがなで入力してください。'
    );

    public function isValid($value) {
        mb_regex_encoding("UTF-8");
        if (!preg_match("/^[ぁ-んー]+$/u", $value)) {
            $this->_error(self::NO_MATCH);
            return false;
        }
        return true;
    }
}
?>
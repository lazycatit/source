<?php

class Validate_ColorCode extends Zend_Validate_Abstract {

    const NOT_COLOR_CODE = 'colorCodeNotColorCode';
    const STRING_EMPTY = 'colorCodeStringEmpty';

    protected $_messageTemplates = array(
    self::NOT_COLOR_CODE => 'カラーの形式が違います。',
    self::STRING_EMPTY => '入力必須です。'
    );

    public function isValid($value) {
        if ('' === $value) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }
        if (!preg_match('/^#{1}[a-fA-F0-9]{6}$/', $value)) {
            $this->_error(self::NOT_COLOR_CODE);
            return false;
        }
        return true;
    }

}

?>
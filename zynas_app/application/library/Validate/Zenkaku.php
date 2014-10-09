<?php

class Validate_Zenkaku extends Zend_Validate_Abstract {
    const STRING_EMPTY = 'stringEmpty';
    const InvalidZenkaku = 'InvalidZenkaku';

    protected $_messageTemplates = array(
    self::InvalidZenkaku => '全角で入力してください。',
    self::STRING_EMPTY => '入力必須です。'
    );

    public function isValid($value) {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        if ('' === $valueString) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }

        if (preg_match('/(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])|[\x20-\x7E]/', $valueString)) {
            $this->_error(self::InvalidZenkaku);
            return false;
        }


        return true;
    }
}
?>
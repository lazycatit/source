<?php

class Validate_HtmlSelect extends Zend_Validate_Abstract {

    const OPTION_UNSELECTED = 'htmlSelectOptionUnselected';
    const OPTION_INVALID = 'htmlSelectOptionInvalid';

    protected $_messageTemplates = array(
    self::OPTION_UNSELECTED => '選択必須です。',
    self::OPTION_INVALID => '不正な値です。'
    );

    public function isValid($value, $options = array()) {
        if (!$value) {
            $this->_error(self::OPTION_UNSELECTED);
            return false;
        }
        if (count($options) > 0 && !in_array($value, $options)) {
            $this->_error(self::OPTION_INVALID);
            return false;
        }
        return true;
    }

}

?>
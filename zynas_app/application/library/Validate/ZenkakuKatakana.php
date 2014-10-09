<?php

class Validate_ZenkakuKatakana extends Zend_Validate_Abstract {
    const InvalidZenkakuKatakana = 'InvalidZenkakuKatakana';

    protected $_messageTemplates = array(
    self::InvalidZenkakuKatakana => '全角カタカナで入力してください。',
    );

    public function isValid($value) {
        mb_regex_encoding("UTF-8");
        $valueString = (string) $value;
        if (!mb_ereg("^[ァ-ヶ 　ー]+$",$valueString)) {
            $this->_error(self::InvalidZenkakuKatakana);
            return false;
        }
        return true;
    }
}
?>
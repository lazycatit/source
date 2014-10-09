<?php

class Validate_BirthdayYmd extends Zend_Validate_Abstract {

    const INVALID = 'invalid';

    protected $_messageTemplates = array(
    self::INVALID => '正しい日付を入力してください。'
    );

    public function isValid($value) {
        $tempYmdhis = explode(' ', $value);
        $tempYmd = explode('-', $tempYmdhis[0]);

        $input = $tempYmd[0] . '-' . sprintf("%02d", $tempYmd[1]) . '-' . sprintf("%02d", $tempYmd[2]);
        $validator = new Zend_Validate_Date();
        if (!$validator->isValid($input)) {
            $this->_error(self::INVALID);
            return false;
        }
        return true;
    }
}
?>
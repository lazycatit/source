<?php

class Validate_DateAll extends Zend_Validate_Abstract {

    const INVALID_INPUT_FORMAT = 'dateInvalidInputFormat';
    const INVALID_DATE = 'dateInvalidDate';
    const INVALID_TIME = 'datetimeInvalidTime';

    protected $_messageTemplates = array(
        self::INVALID_INPUT_FORMAT => E073V,
        self::INVALID_DATE => "存在しない日付です。",
        self::INVALID_TIME => "存在しない時刻です。"
    );

    public function __construct($datetimeToCompare = null) {
        $this->_datetimeToCompareInUts = $datetimeToCompare ? strtotime($datetimeToCompare) : time();
    }

    public function isValid($value) {

        $checkDate = false;
        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}/', $value)
            ||preg_match('/^[0-9]{4}-[0-9]{1}-[0-9]{1}/', $value)
            ||preg_match('/^[0-9]{4}-[0-9]{1}-[0-9]{2}/', $value)
            ||preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{1}/', $value)) {
            $checkDate = true;
        }

        if(!$checkDate){
            $this->_error(self::INVALID_INPUT_FORMAT);
            return false;
        }

        return true;
    }

}

?>
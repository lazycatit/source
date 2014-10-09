<?php

class Zynas_Validate_Count extends Zend_Validate_Abstract {

    const NOT_BETWEEN = 'notBetween';
    const NOT_BETWEEN_STRICT = 'notBetweenStrict';

    protected $_messageTemplates = array(
        self::NOT_BETWEEN => "%min%個以上、%max%個以下ではありません",
        self::NOT_BETWEEN_STRICT => "%min%個より上、%max%個未満ではありません",
    );

    protected $_messageVariables = array(
        'min' => '_min',
        'max' => '_max'
    );

    protected $_min;
    protected $_max;
    protected $_inclusive;

    public function __construct($min, $max, $inclusive = true) {
        $this->_min = intval($min);
        $this->_max = intval($max);
        $this->_inclusive = (bool) $inclusive;
    }

    public function isValid($value) {
        $value = count((array) $value);
        if ($this->_inclusive) {
            if ($this->_min > $value || $value > $this->_max) {
                $this->_error(self::NOT_BETWEEN);
                return false;
            }
        }
        else {
            if ($this->_min >= $value || $value >= $this->_max) {
                $this->_error(self::NOT_BETWEEN_STRICT);
                return false;
            }
        }
        return true;
    }

}

?>
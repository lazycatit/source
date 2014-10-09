<?php
class Validate_Checkbox extends Zend_Validate_Abstract {
    const NOT_CHECK_ALL = 'isNotCheckAll';
    
    protected $_messageTemplates = array(
        self::NOT_CHECK_ALL => E032V,
    );
    
    public function __construct() {
        
    }
    
    public function isValid($value) {
        if (strcmp($value, '1') !== 0) {
            $this->_error(self::NOT_CHECK_ALL);
            return false;
        }
        return true;
    }
}
?>    


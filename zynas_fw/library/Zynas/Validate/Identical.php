<?php

class Zynas_Validate_Identical extends Zend_Validate_Identical {
	
    protected $_messageTemplates = array(
        self::NOT_SAME      => 'Password is not same',
        self::MISSING_TOKEN => ''
    );
    
    public function isValid($value, $context = null)
    {
    	parent::isValid($value, $context);
    }
}
?>
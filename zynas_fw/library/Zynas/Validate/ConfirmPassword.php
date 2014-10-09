<?php
class Zynas_Validate_ConfirmPassword extends Zend_Validate_Abstract
{
const NOT_MATCH = 'notMatch';

    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Password confirmation does not match'
    );

    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);

        var_dump($value);
        var_dump($context);
        
        
        if (is_array($context)) {
            if (isset($context['password']) 
               && ($value == $context['password']))
            {
                return true;
            }
        } 
        elseif (is_string($context) && ($value == $context)) {
            return true;
        }

        $this->_error(self::NOT_MATCH);
        return false;
    }
	
}
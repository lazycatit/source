<?php

class Validate_DuCustomerCode extends Zend_Validate_Abstract {

    const IS_DISCO = 'isDisco';

    protected $_messageTemplates = array(
    self::IS_DISCO => "お客さまに、ディスコ以外を選択して下さい。",
    );

    public function isValid($value) {
        if(strcmp($value, Zynas_Registry::getConfig() -> customer -> disco_customer_code) === 0){
            $this->_error(self::IS_DISCO);
            return false;
        }
        return true;
    }

}

?>
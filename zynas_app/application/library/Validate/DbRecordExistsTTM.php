<?php

class Validate_DbRecordExistsTTM extends Zend_Validate_Db_RecordExists {

    protected $_messageTemplates = array(
    self::ERROR_NO_RECORD_FOUND => E071V,
    );
    
}

?>

<?php

class Validate_Db_NoRecordExistsTTM extends Zend_Validate_Db_NoRecordExists {
    protected $_messageTemplates = array(
    self::ERROR_RECORD_FOUND => E071V
    );
}

?>
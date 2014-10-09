<?php

class Zynas_Validate_Db_RecordExists extends Zend_Validate_Db_RecordExists {
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => '「%value%」はDB登録されていません。'
    );
}

?>
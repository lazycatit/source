<?php

class Zynas_Validate_Db_NoRecordExists extends Zend_Validate_Db_NoRecordExists {
    protected $_messageTemplates = array(
        self::ERROR_RECORD_FOUND => '「%value%」は既にDB登録されています。'
    );
}

?>
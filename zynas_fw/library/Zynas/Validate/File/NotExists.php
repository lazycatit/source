<?php
class Zynas_Validate_File_NotExists extends Zend_Validate_File_NotExists {
    protected $_messageTemplates = array(
        self::DOES_EXIST => 'ファイルが存在しません。'
    );
}
?>
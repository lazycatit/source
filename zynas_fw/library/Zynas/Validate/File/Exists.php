<?php
class Zynas_Validate_File_Exists extends Zend_Validate_File_Exists {
    protected $_messageTemplates = array(
        self::DOES_NOT_EXIST => 'ファイルが、存在しません。'
    );
}
?>
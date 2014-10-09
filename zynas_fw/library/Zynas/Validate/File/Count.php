<?php
class Zynas_Validate_File_Count extends Zend_Validate_File_Count {
    protected $_messageTemplates = array(
        self::TOO_MUCH => 'ファイルが多すぎます。%value%のみ許可されています。',
        self::TOO_LESS => 'ファイルが少なすぎます。最低%value%必要です。'
    );
}
?>
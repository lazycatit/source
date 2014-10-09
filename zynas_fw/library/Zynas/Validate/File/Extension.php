<?php
class Zynas_Validate_File_Extension extends Zend_Validate_File_Extension {
    protected $_messageTemplates = array(
        self::FALSE_EXTENSION => '使用出来ない拡張子です。',
        self::NOT_FOUND       => '拡張子が見つかりません。'
    );
}
?>
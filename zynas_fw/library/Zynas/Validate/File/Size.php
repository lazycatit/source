<?php
class Zynas_Validate_File_Size extends Zend_Validate_File_Size {
    protected $_messageTemplates = array(
        self::TOO_BIG   => 'ファイルが許可されているサイズを超えています。',
        self::TOO_SMALL => 'ファイルが許可されているサイズに足りていません。',
        self::NOT_FOUND => 'ファイルサイズを検出出来ませんでした。'
    );
}
?>
<?php
class Zynas_Validate_Barcode_UpcA extends Zend_Validate_Barcode_UpcA {
    protected $_messageTemplates = array(
        self::INVALID        => '有効なUPC-Aバーコードではありません。',
        self::INVALID_LENGTH => '12文字である必要があります。'
    );
}
?>
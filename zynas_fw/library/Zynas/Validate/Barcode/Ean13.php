<?php
class Zynas_Validate_Barcode_Ean13 extends Zend_Validate_Barcode_Ean13 {
    protected $_messageTemplates = array(
        self::INVALID        => '有効なEAN-13バーコードではありません。',
        self::INVALID_LENGTH => '13文字である必要があります。'
    );
}
?>
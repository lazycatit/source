<?php
class Zynas_Validate_Hex extends Zend_Validate_Hex {
    protected $_messageTemplates = array(
        self::NOT_HEX => '16進数ではありません。'
    );
}
?>
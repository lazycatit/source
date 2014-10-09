<?php
class Zynas_Validate_Float extends Zend_Validate_Float {
    protected $_messageTemplates = array(
        self::NOT_FLOAT => '浮動小数点数ではありません。'
    );
}
?>
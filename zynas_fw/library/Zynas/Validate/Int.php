<?php
class Zynas_Validate_Int extends Zend_Validate_Int {
    protected $_messageTemplates = array(
        self::NOT_INT => '整数ではありません。'
    );
}
?>
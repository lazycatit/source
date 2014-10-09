<?php
class Zynas_Validate_NotEmpty extends Zend_Validate_NotEmpty {
    protected $_messageTemplates = array(
        self::IS_EMPTY => '入力必須項目です。'
    );
}
?>
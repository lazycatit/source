<?php
class Zynas_Validate_Alnum extends Zend_Validate_Alnum {
    protected $_messageTemplates = array(
        self::NOT_ALNUM    => 'アルファベットと数字以外が含まれています。',
        self::STRING_EMPTY => '入力されていません。'
    );
}
?>
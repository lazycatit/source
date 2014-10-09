<?php
class Zynas_Validate_Digits extends Zend_Validate_Digits {
    protected $_messageTemplates = array(
        self::NOT_DIGITS   => '半角数字以外を含んでいます。',
        self::STRING_EMPTY => '入力されていません。'
    );
}
?>
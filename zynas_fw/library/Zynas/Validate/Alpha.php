<?php
class Zynas_Validate_Alpha extends Zend_Validate_Alpha {
    protected $_messageTemplates = array(
        self::NOT_ALPHA    => 'アルファベット以外の文字が含まれています。',
        self::STRING_EMPTY => '入力されていません。'
    );
}
?>
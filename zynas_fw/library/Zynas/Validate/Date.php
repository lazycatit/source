<?php
class Zynas_Validate_Date extends Zend_Validate_Date {
    protected $_messageTemplates = array(
        self::INVALID => '有効な日付ではありません。',
        self::INVALID_DATE => '有効な日付ではありません。',
        self::FALSEFORMAT => '正しい日時を入力してください。'
    );
}
?>
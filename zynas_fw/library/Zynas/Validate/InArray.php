<?php
class Zynas_Validate_InArray extends Zend_Validate_InArray {
    protected $_messageTemplates = array(
        self::NOT_IN_ARRAY => '見つかりませんでした'
    );
}
?>
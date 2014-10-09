<?php
class Zynas_Validate_LessThan extends Zend_Validate_LessThan {
    protected $_messageTemplates = array(
        self::NOT_LESS => '%max%以下ではありません。'
    );
}
?>
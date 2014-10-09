<?php
class Zynas_Validate_Regex extends Zend_Validate_Regex {
    protected $_messageTemplates = array(
        self::NOT_MATCH => '%pattern%のパターンにはマッチしません。'
    );
}
?>
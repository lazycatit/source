<?php
class Zynas_Validate_Between extends Zend_Validate_Between {
    protected $_messageTemplates = array(
        self::NOT_BETWEEN        => '%min%から%max%の間にありません。',
        self::NOT_BETWEEN_STRICT => '厳密に言うと、%min%から%max%の間にはありません。'
    );
}
?>
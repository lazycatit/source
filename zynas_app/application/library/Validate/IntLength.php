<?php
class Validate_IntLength extends Zend_Validate_StringLength {
    protected $_messageTemplates = array(
    self::TOO_SHORT => '%min%桁以上で入力してください。',
    self::TOO_LONG  => '%max%桁以内で入力してください。'
    );
}
?>
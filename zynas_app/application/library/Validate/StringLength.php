<?php
class Validate_StringLength extends Zend_Validate_StringLength {
    protected $_messageTemplates = array(
    self::TOO_SHORT => E070V,
    self::TOO_LONG  => E069V
    );
}
?>
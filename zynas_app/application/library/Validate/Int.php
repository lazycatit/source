<?php
class Validate_Int extends Zynas_Validate_Int {
    protected $_messageTemplates = array(
    self::NOT_INT => '半角数値ではありません。'
    );
}
?>
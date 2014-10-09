<?php
class Validate_GreaterThan extends Zynas_Validate_GreaterThan {

    protected $_messageTemplates = array(
    self::NOT_GREATER => '%min%以上ではありません。'
    );

    public function isValid($value)
    {
        $this->_setValue($value);

        if ($this->_min > $value) {
            $this->_error(self::NOT_GREATER);
            return false;
        }
        return true;
    }
}
?>

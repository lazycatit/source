<?php
class Validate_LessThan extends Zynas_Validate_LessThan {
    protected $_messageTemplates = array(
    self::NOT_LESS => '%max%以下ではありません。'
    );

    public function isValid($value)
    {
        $this->_setValue($value);
        if ($this->_max < $value) {
            $this->_error(self::NOT_LESS);
            return false;
        }
        return true;
    }
}
?>

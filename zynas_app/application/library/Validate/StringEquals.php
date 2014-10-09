<?php

class Validate_StringEquals extends Zynas_Validate_Abstract {

    const NOT_EQUAL = 'notEqual';

    protected $_messageTemplates = array(
    self::NOT_EQUAL => 'ふたつの入力が照合しません。'
    );

    public function isValid($values) {
        // side: フィールド名でキーが指定されているので、配列に変換します。
        $values = array_values($values);
        if ($values[0] === $values[1]) {
            return true;
        }
        else {
            $this->_error(self::NOT_EQUAL);
            return false;
        }
    }
}

?>
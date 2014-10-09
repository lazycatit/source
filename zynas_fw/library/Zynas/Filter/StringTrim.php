<?php

class Zynas_Filter_StringTrim extends Zend_Filter_StringTrim {

    // side:
    // オブジェクトで文字列化できないものをフィルタ通すとエラーになるので修正を加えています。
    // 他のデータ型で文字列化できないもの（リソースなど）はさすがに飛んでこないだろう、という想定でそれらについては除外していません。
    public function filter($value) {
        if (!is_string($value) || (is_object($value) && !method_exists($value, '__toString'))) return $value;
        return parent::filter($value);
    }
}

?>
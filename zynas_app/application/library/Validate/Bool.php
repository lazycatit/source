<?php

class Validate_Bool extends Zend_Validate_Abstract {

    const NOT_BOOL = 'boolNotBool';

    protected $_messageTemplates = array(
    self::NOT_BOOL => "選択必須です。" // side: Bool値はラジオボタンでの実装になるので。
    );

    public function isValid($value) {
        // side: HTTPリクエストでは整数型も文字列型で渡されるので文字列として比較します。
        return ($value === '0' || $value === '1');
    }

}

?>
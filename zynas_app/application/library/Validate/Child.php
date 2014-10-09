<?php

class Validate_Child extends Zend_Validate_Abstract {

    const INVALID = 'invalid';

    protected $_messageTemplates = array(
    self::INVALID => '大人と未成年の子供 の場合は 一番下のお子さんの年齢 を入力してください。'
    );

    public function isValid($value) {
        if($value == 3 AND strcmp($_POST['youngest_child_birth_year'], '') === 0) {
            $this->_error(self::INVALID);
            return false;
        }

        return true;
    }

}

?>
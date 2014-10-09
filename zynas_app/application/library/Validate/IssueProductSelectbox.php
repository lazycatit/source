<?php

class Validate_IssueProductSelectbox extends Zend_Validate_Abstract {

    const NOT_CHOOSEN = 'selectboxNotChoosen';

    protected $_messageTemplates = array(
    self::NOT_CHOOSEN => "選択していない情報を選択してください。",
    );

    public function isValid($value) {
        if ('00' === $value) {
            $this->_error(self::NOT_CHOOSEN);
            return false;
        }

        return true;
    }

}

?>
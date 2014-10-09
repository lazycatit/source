<?php

class Validate_Db_RecordExistsTTM extends Zend_Validate_Db_RecordExists {
    protected $_messageTemplates = array(
    self::ERROR_NO_RECORD_FOUND => '※入力されたユーザIDまたはパスワードに誤りがあります。'
    );
}

?>
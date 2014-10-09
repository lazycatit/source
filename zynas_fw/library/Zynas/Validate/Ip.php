<?php
class Zynas_Validate_Ip extends Zend_Validate_Ip {
    protected $_messageTemplates = array(
        self::NOT_IP_ADDRESS => '有効なIPアドレスではありません。'
    );
}
?>
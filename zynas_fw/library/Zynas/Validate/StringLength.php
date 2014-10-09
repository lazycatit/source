<?php
class Zynas_Validate_StringLength extends Zend_Validate_StringLength {
    protected $_messageTemplates = array(
        self::TOO_SHORT => '%min%譁�ｭ嶺ｻ･荳翫〒蜈･蜉帙＠縺ｦ縺上□縺輔＞縲�',
        self::TOO_LONG  => '%max%譁�ｭ嶺ｻ･蜀�〒蜈･蜉帙＠縺ｦ縺上□縺輔＞縲�'
    );
}
?>
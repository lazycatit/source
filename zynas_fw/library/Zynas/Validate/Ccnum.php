<?php
class Zynas_Validate_Ccnum extends Zend_Validate_Ccnum {
    protected $_messageTemplates = array(
        self::LENGTH   => '13~19蟄怜性繧�ｿ�ｦ√′縺ゅｊ縺ｾ縺吶�',
        self::CHECKSUM => '繝ｫ繝ｼ繝ｳ蠑上メ繧ｧ繝�け(mod-10 繝√ぉ繝�け繧ｵ繝�縺ｫ螟ｱ謨励＠縺ｾ縺励◆縲�'
    );
}
?>
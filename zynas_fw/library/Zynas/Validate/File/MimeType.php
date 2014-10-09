<?php
class Zynas_Validate_File_MimeType extends Zend_Validate_File_MimeType {
    protected $_messageTemplates = array(
        self::FALSE_TYPE   => '繝輔ぃ繧､繝ｫ蠖｢蠑上′豁｣縺励￥縺ゅｊ縺ｾ縺帙ｓ縲�',
        self::NOT_DETECTED => '繝輔ぃ繧､繝ｫ蠖｢蠑上ｒ讀懷�蜃ｺ譚･縺ｾ縺帙ｓ縲�',
        self::NOT_READABLE => '繝輔ぃ繧､繝ｫ繧定ｪｭ縺ｿ霎ｼ繧√∪縺帙ｓ縺ｧ縺励◆縲�'
    );
}
?>
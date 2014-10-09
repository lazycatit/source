<?php
class Zynas_Validate_File_ImageSize extends Zend_Validate_File_ImageSize {
    protected $_messageTemplates = array(
        self::WIDTH_TOO_BIG    => '逕ｻ蜒上�蟷�′縲∬ｨｱ蜿ｯ縺輔ｌ縺溘ｂ縺ｮ繧医ｊ螟ｧ縺阪＞縺ｧ縺吶�',
        self::WIDTH_TOO_SMALL  => '逕ｻ蜒上�蟷�′縲∬ｨｱ蜿ｯ縺輔ｌ縺溘ｂ縺ｮ繧医ｊ蟆上＆縺�〒縺吶�',
        self::HEIGHT_TOO_BIG   => '逕ｻ蜒上�邵ｦ縺後�險ｱ蜿ｯ縺輔ｌ縺溘ｂ縺ｮ繧医ｊ螟ｧ縺阪＞縺ｧ縺吶�',
        self::HEIGHT_TOO_SMALL => '逕ｻ蜒上�邵ｦ縺後�險ｱ蜿ｯ縺輔ｌ縺溘ｂ縺ｮ繧医ｊ蟆上＆縺�〒縺吶�',
        self::NOT_DETECTED     => '逕ｻ蜒上し繧､繧ｺ繧偵�讀懷�蜃ｺ譚･縺ｾ縺帙ｓ縺ｧ縺励◆縲�',
        self::NOT_READABLE     => '逕ｻ蜒上ヵ繧｡繧､繝ｫ繧定ｪｭ縺ｿ霎ｼ繧√∪縺帙ｓ縺ｧ縺励◆縲�'
    );
}
?>
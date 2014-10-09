<?php
class Zynas_Validate_EmailAddress extends Zend_Validate_EmailAddress {
    protected $_messageTemplates = array(
        self::INVALID            => '蝓ｺ譛ｬ逧�↑繝｡繝ｼ繝ｫ繧｢繝峨Ξ繧ｹ縺ｮ繝輔か繝ｼ繝槭ャ繝医〒縺ｯ縺ゅｊ縺ｾ縺帙ｓ縲�',
        self::INVALID_FORMAT     => '繝｡繝ｼ繝ｫ繧｢繝峨Ξ繧ｹ縺ｮ蠖｢蠑上〒縺ｯ縺ゅｊ縺ｾ縺帙ｓ縲�',
        self::INVALID_HOSTNAME   => '%hostname%縺後�繝｡繝ｼ繝ｫ繧｢繝峨Ξ繧ｹ縺ｮ譛牙柑縺ｪ繝帙せ繝亥錐縺ｧ縺ｯ縺ゅｊ縺ｾ縺帙ｓ縲�',
        self::INVALID_MX_RECORD  => '%hostname%縺後�繝｡繝ｼ繝ｫ繧｢繝峨Ξ繧ｹ縺ｮ譛牙柑縺ｪMX繝ｬ繧ｳ繝ｼ繝峨↓縺ｯ縺ゅｊ縺ｾ縺帙ｓ縲�',
        self::DOT_ATOM           => '%localPart%縺後�繝峨ャ繝医ヵ繧ｩ繝ｼ繝槭ャ繝医↓繝槭ャ繝√＠縺ｾ縺帙ｓ縲�',
        self::QUOTED_STRING      => '%localPart%縺後�莠碁㍾蠑慕畑隨ｦ繝輔か繝ｼ繝槭ャ繝医↓繝槭ャ繝√＠縺ｾ縺帙ｓ縲�',
        self::INVALID_LOCAL_PART => '%localPart%縺ｯ縲√Γ繝ｼ繝ｫ繧｢繝峨Ξ繧ｹ縺ｮ譛牙柑縺ｪ繝ｭ繝ｼ繧ｫ繝ｫ縺ｧ縺ｯ縺ゅｊ縺ｾ縺帙ｓ縲�'
    );
}
?>
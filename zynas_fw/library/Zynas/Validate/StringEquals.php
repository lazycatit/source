<?php

class Zynas_Validate_StringEquals extends Zynas_Validate_Abstract {

    const NOT_EQUAL = 'notEqual';

    protected $_messageTemplates = array(
        self::NOT_EQUAL => '縺ｵ縺溘▽縺ｮ蜈･蜉帙′辣ｧ蜷医＠縺ｾ縺帙ｓ縲�'
    );

    public function isValid($values) {
        // side: 繝輔ぅ繝ｼ繝ｫ繝牙錐縺ｧ繧ｭ繝ｼ縺梧欠螳壹＆繧後※縺�ｋ縺ｮ縺ｧ縲��蛻励↓螟画鋤縺励∪縺吶�
        $values = array_values($values);
        if ($values[0] === $values[1]) {
            return true;
        }
        else {
            $this->_error();
            return false;
        }
    }
}

?>
<?php

class View_Helper_EscapeIfNotEmpty extends Zynas_View_Helper {

    const TEXT_EMPTY = '<em>設定なし</em>';

    public function escapeIfNotEmpty($var, $unit = null) {
        return (isset($var) && !empty($var)) ? $this->getView()->escape($var) . $unit : self::TEXT_EMPTY;
    }

}
?>
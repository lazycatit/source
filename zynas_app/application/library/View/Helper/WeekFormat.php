<?php

class View_Helper_WeekFormat extends Zynas_View_Helper {
    private static $_weekNames = array('日', '月', '火', '水', '木', '金', '土');

    public function weekFormat($var) {
        if ($var == '') return '';
        return self::$_weekNames[date('w', strtotime($var))];
    }
}

?>
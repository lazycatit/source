<?php

class View_Helper_DatetimeAgo extends Zynas_View_Helper {
    public function datetimeAgo($var, $format = 'Y年m月d日H時i分') {
        if ($var == '') return '';
        // side: UTSだけでなくZend|Zynas_Dateインスタンスや文字列も受け付けられるようにする。
        if (!is_int($var)) $var = strtotime((string) $var);
        $diff = time() - $var;
        if ($diff <= 1) {
            return '1秒前';
        }
        else if ($diff <= 60) {
            return $diff . '秒前';
        }
        else if ($diff < 3600) {
            return floor($diff / 60) . '分前';
        }
        else if ($diff < 86400) {
            return floor($diff / 60 / 60) . '時間前';
        }
        else if ($diff < (86400 * 7)) {
            return floor($diff / 60 / 60 / 24) . '日前';
        }
        else if ($diff < (86400 * 30)) {
            return floor($diff / 60 / 60 / 24 / 7) .'週間前';
        }
        else if ($diff < (86400 * 365)) {
            return floor($diff / 60 / 60 / 24 / 30) .'ヶ月前';
        }
        else {
            return date($diff, $format);
        }
    }
}

?>
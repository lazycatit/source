<?php

class View_Helper_DatetimeFormat extends Zynas_View_Helper {
    public function datetimeFormat($var, $format = 'Y年n月j日H時i分') {
        if ($var == '') return '';
        return htmlspecialchars(date($format, strtotime($var)));
    }
}

?>
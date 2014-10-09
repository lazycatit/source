<?php

class View_Helper_DatetimeFormat extends Zynas_View_Helper {
    public function datetimeFormat($var, $format = 'Y.m.d　 H:i') {
        if ($var == '') return '';
        return htmlspecialchars(date($format, strtotime($var)));
    }
}

?>
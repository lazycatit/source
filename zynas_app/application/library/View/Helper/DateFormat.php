<?php

class View_Helper_DateFormat extends Zynas_View_Helper {
    public function dateFormat($var, $format = 'Y.m.d') {
        if ($var == '') return '';
        return htmlspecialchars(date($format, strtotime($var)));
    }
}

?>
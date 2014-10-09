<?php

class View_Helper_DateBrTimeFormat extends Zynas_View_Helper {
    public function dateBrTimeFormat($var, $formatDate = 'Y/m/d', $formatTime = 'H:i') {
        if ($var == '') return '';
        return htmlspecialchars(date($formatDate, strtotime($var))) . '<br>' . htmlspecialchars(date($formatTime, strtotime($var)));
    }
}

?>
<?php

class View_Helper_Truncate extends Zynas_View_Helper {
    public function truncate($var, $numChars, $stripHtml = true, $suffix = '...') {
        $var = $stripHtml ? strip_tags($var) : $this->getView()->escape($var);
        $len = mb_strlen($var);
        return $len <= $numChars ? $var : mb_substr($var, 0, $numChars - mb_strlen($suffix)) . $suffix;
    }
}

?>
<?php

class View_Helper_FormError extends Zynas_View_Helper {
    public function formError($key, $errors = array(), $tag = null) {
        if (is_null($tag)) $tag = '<div style="color:#f00;font-weight:bold;">%s</div>';
        return isset($errors[$key]) ? sprintf($tag, htmlspecialchars($errors[$key])) : '';
    }
}
?>
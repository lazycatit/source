<?php

class View_Helper_FormErrors extends Zynas_View_Helper {
    public function formErrors($key, $errors = array(), $tag = null) {
        if (is_null($tag)) $tag = '<div style="color:#B8860B;font-weight:bold;padding: 3px 0 0 0;">%s</div>';
        $string = null;
        if (isset($errors[$key])) {
            if (!is_array($errors[$key])) {
                $string = htmlspecialchars($errors[$key]);
            }
            else {
                $tmp = array();
                foreach ($errors[$key] as $error) {
                    $tmp[] = htmlspecialchars($error);
                }
                $string = implode('<br />', $tmp);
            }
        }
        return !empty($string) ? sprintf($tag, $string) : '';
    }
}
?>
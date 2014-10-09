<?php

class View_Helper_FormPublicError extends Zynas_View_Helper {

    public function formPublicError($value, $tag = '<span class="alert_message">%s</span>') {
        if ($this->getView()->errors && is_array($this->getView()->errors) && array_key_exists($value, $this->getView()->errors)) {
            return sprintf($tag, $this->getView()->errors[$value]);
        }
        else {
            return;
        }
    }

}

?>
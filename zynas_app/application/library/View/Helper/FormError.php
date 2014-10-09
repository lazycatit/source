<?php

class View_Helper_FormError extends Zynas_View_Helper {

    public function formError($value, $tag = '<span class="alert">%s</span>') {
        if ($this->getView()->errors && is_array($this->getView()->errors) && array_key_exists($value, $this->getView()->errors)) {
            return sprintf($tag, $this->getView()->errors[$value]);
        }
        else {
            return;
        }
    }

}

?>
<?php

class Zynas_View_Helper_Nl2br extends Zynas_View_Helper {
    public function nl2br($var) {
        return nl2br($this->getView()->escape($var));
    }
}

?>
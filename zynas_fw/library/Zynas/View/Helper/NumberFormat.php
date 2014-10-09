<?php

class Zynas_View_Helper_NumberFormat extends Zynas_View_Helper {
    public function numberFormat($var, $decimals = 0) {
        return $this->getView()->escape(number_format($var, $decimals));
    }
}

?>
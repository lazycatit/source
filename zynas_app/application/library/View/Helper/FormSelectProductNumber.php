<?php

class View_Helper_FormSelectProductNumber extends Zend_View_Helper_FormSelect {
    
    public function formSelectProductNumber($name, $value = null, $attribs = null) {
        $productNumberData = MProducts::getInstance() -> getListProductNumber(MCategorys::CATEGORY_DEVICE);
        $option = array();
        foreach ($productNumberData as $item) {
            $option[$item -> product_number] = $item -> product_number;
        }
        $option['00'] = '選択して下さい';
        return $this->formSelect($name, $value, $attribs, $option);
    }
}

?>
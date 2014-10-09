<?php

class View_Helper_FormSelectCategoryName2 extends Zend_View_Helper_FormSelect {

    public function formSelectCategoryName2($name, $value = null, $attribs = null) {

        $categoryName = MCategorys::getInstance() -> getPadSerial();
        $option = array();
        foreach ($categoryName as $item) {
            $option[$item -> sub_category] = $item -> name1;
        }
        $option['00'] = '選択して下さい';
        return $this->formSelect($name, $value, $attribs, $option);
    }
}

?>
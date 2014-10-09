<?php

class View_Helper_FormSelectMainCategory extends Zend_View_Helper_FormSelect {
    
    public function formSelectMainCategory($name, $value = null, $attribs = null) {
        $mainCategoryData = MCategorys::getInstance() -> getMainProductCategory();
        $option = array();
        foreach ($mainCategoryData as $item) {
            $option[$item -> main_category] = $item -> name1;
        }
        $option[MCategorys::CATEGORY_ZERO] = '選択して下さい';
        return $this->formSelect($name, $value, $attribs, $option);
    }
}

?>
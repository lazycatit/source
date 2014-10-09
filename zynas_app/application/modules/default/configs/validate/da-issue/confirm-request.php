<?php
$validator = array('*' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, ), );


$publishTypeCheck = array('publish_type' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, ), );
$validator = array_merge($validator, $publishTypeCheck);

if(isset($_REQUEST['publish_type']) && in_array(TPublishInfos::PUBLISH_TYPE_PAPER, $_REQUEST['publish_type'])) {
    $postalUnitCheck = array('postal_unit'  => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), ), );
    $validator = array_merge($validator, $postalUnitCheck);
    $postalNameCheck = array('postal_name'  => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), ), );
    $validator = array_merge($validator, $postalNameCheck);
}

if(isset($_REQUEST['rd_customer_designation']) && strcmp($_REQUEST['rd_customer_designation'], TPublishInfos::IS_CUSTOMER_DESIGNATION) === 0) {
    $customerDesignationTextCheck = array('txt_customer_designation'  => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '255', 'encoding' => 'UTF-8')), ), );
    $validator = array_merge($validator, $customerDesignationTextCheck);
}

if (isset($_REQUEST['products'])) {
    foreach ($_REQUEST['products'] as $key => $productIndex) {
        //Validate the main product category select box
        if(isset($_REQUEST['select_main_product_category_'.$productIndex])) {
            $mainProductCategoryCheck = array('select_main_product_category_' . $productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('IssueProductSelectbox'), ), );
            $validator = array_merge($validator, $mainProductCategoryCheck);
            //If product category is device
            if (strcmp($_REQUEST['select_main_product_category_' . $productIndex], MCategorys::CATEGORY_DEVICE) === 0) {
                //Validate the product number select box
                if(isset($_REQUEST['select_product_number_'.$productIndex])) {
                    $productNumberCheck = array('select_product_number_' . $productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('IssueProductSelectbox'), ), );
                    $validator = array_merge($validator, $productNumberCheck);
                }
                //Validate the product serial textbox
                if(isset($_REQUEST['text_serial_number_'.$productIndex])) {
                    $serialNumberCheck = array('text_serial_number_' . $productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False,
                    array('StringLength', array('max' => '6', 'encoding' => 'UTF-8')),
                    ), );
                    $validator = array_merge($validator, $serialNumberCheck);
                }
            } elseif (strcmp($_REQUEST['select_main_product_category_' . $productIndex], MCategorys::CATEGORY_SUBDEVICE) === 0) {
                //Validate the product number view textbox
                if(isset($_REQUEST['text_product_number_view_'.$productIndex])) {
                    $productNumberViewCheck = array('text_product_number_view_' . $productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '25', 'encoding' => 'UTF-8')), array('DbRecordExistsTTM', array('field' => 'product_number_view', 'table' => 'm_product')), ), );
                    $validator = array_merge($validator, $productNumberViewCheck);
                }
            } elseif (strcmp($_REQUEST['select_main_product_category_' . $productIndex], MCategorys::CATEGORY_PWP) === 0) {
                //Validate the model/serial selectbox
                $modelSerialCheck = array('select_model_serial_' . $productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('IssueProductSelectbox'), ), );
                $validator = array_merge($validator, $modelSerialCheck);
                if (strcmp($_REQUEST['select_model_serial_' . $productIndex], '01') === 0) {
                    //Validate the product number code
                    if(isset($_REQUEST['text_product_number_view_'.$productIndex])) {
                        $productNumberCheck = array('text_product_number_' . $productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False,
                        array('StringLength', array('max' => '25', 'encoding' => 'UTF-8')),
                        array('DbRecordExistsTTM', array('field' => 'product_number', 'table' => 'm_product')), ), );
                        $validator = array_merge($validator, $productNumberCheck);
                    }
                } elseif (strcmp($_REQUEST['select_model_serial_' . $productIndex], '02') === 0) {
                    //Validate the product category
                    if(isset($_REQUEST['text_product_number_view_'.$productIndex])) {
                        $categoryName2Check = array('select_category_name2_' . $productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('IssueProductSelectbox'), ), );
                        $validator = array_merge($validator, $categoryName2Check);
                    }
                }
            }
        }
    }
}

return $validator;
?>
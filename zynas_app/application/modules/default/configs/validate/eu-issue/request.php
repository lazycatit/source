<?php
$validator = array('*' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, ), );

if (isset($_REQUEST['products'])) {
    foreach($_REQUEST['products'] as $key=>$productIndex){
        $mailCCCheck = array('text_serial_number'.$productIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), array('EmailAddress'), ), );
        $validator = array_merge($validator, $mailCCCheck);
        echo 'index:'.$productIndex;
    }
}

return $validator;


?>
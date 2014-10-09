<?php

$validate = array('*' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, ), );

if (isset($_REQUEST['create_from'])) {
    $createFromCheck = array('create_from' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, array('DateAll'), ), );
    $validate = array_merge($validate, $createFromCheck);
}

if (isset($_REQUEST['create_to'])) {
    $createToCheck = array('create_to' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, array('DateAll'), ), );
    $validate = array_merge($validate, $createToCheck);
}

if (isset($_REQUEST['control_number'])) {
    $controlNumberCheck = array('control_number' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, array('StringLength', array('max' => '10', 'encoding' => 'UTF-8')), array('ControlNumber'), ), );
    $validate = array_merge($validate, $controlNumberCheck);
}

if (isset($_REQUEST['customer_code'])) {
    $controlNumberCheck = array('customer_code' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, ));
    $validate = array_merge($validate, $controlNumberCheck);
}

return $validate;
?>
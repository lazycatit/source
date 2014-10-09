<?php

$finalCheck = array();

$basicCheck = array('*' => array(Zynas_Filter_Input::ALLOW_EMPTY => True, ), );

if (isset($_REQUEST['postal_code'])) {
    $postalCodeCheck = array('postal_code' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '10', 'encoding' => 'UTF-8')), array('PostalCode'), ), );
    $finalCheck = array_merge($basicCheck, $postalCodeCheck);
}

if (isset($_REQUEST['customer_name'])) {
    $customerNameCheck = array('customer_name' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), ), );
    $finalCheck = array_merge($finalCheck, $customerNameCheck);
}

if (isset($_REQUEST['customer_address'])) {
    $customerAddressCheck = array('customer_address' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '160', 'encoding' => 'UTF-8')), ), );
    $finalCheck = array_merge($finalCheck, $customerAddressCheck);
}

if (isset($_REQUEST['unit_name'])) {
    $unitNameCheck = array('unit_name' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), ), );
    $finalCheck = array_merge($finalCheck, $unitNameCheck);
}

if (isset($_REQUEST['user_name'])) {
    $userNameCheck = array('user_name' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), ), );
    $finalCheck = array_merge($finalCheck, $userNameCheck);
}

if (isset($_REQUEST['user_mail'])) {
    $userMailCheck = array('user_mail' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), array('EmailAddress'), ), );
    $finalCheck = array_merge($finalCheck, $userMailCheck);
}

if (isset($_REQUEST['customer_tel'])) {
    $customerTelCheck = array('customer_tel' => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '20', 'encoding' => 'UTF-8')), array('PhoneNumber'), ), );
    $finalCheck = array_merge($finalCheck, $customerTelCheck);
}

if (isset($_REQUEST['mailcc'])) {
    foreach($_REQUEST['mailcc'] as $key=>$emailIndex){
        $mailCCCheck = array('text_email_cc'.$emailIndex => array(Zynas_Filter_Input::ALLOW_EMPTY => False, array('StringLength', array('max' => '50', 'encoding' => 'UTF-8')), array('EmailAddress'), ), );
        $finalCheck = array_merge($finalCheck, $mailCCCheck);
    }
}

return $finalCheck;
?>
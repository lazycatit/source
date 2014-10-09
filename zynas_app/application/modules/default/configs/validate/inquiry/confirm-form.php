<?php
return array(
		'email' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 50)),
array('EmailAddress'),
),
        'phone' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('PhoneNumber'),
array('StringLength', array('max' => 20)),
),
        'department' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 100,'encoding'=>'UTF-8')),
),
        'content' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
),
);
?>

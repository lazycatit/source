<?php
return array(
     '*' => array(
Zynas_Filter_Input::ALLOW_EMPTY => true,
),
    'code' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
array('Db_RecordExists', array('field' => 'code', 'table' => 'm_customer')),
),
    'is_customer_designation' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
),
    'txtDivisionName' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
array('StringLength', array('max' => 50)),
),
    'txtStaffName' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
array('StringLength', array('max' => 50)),
),
    'pdf' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
)

);
?>
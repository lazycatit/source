<?php
return array(
     '*' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
),
    'customer_code' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
array('Db_RecordExists', array('field' => 'customer_code', 'table' => 'm_customer')),
),
    'is_customer_designation' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
),
    'pdf' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
)

);


?>
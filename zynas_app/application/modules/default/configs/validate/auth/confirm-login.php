<?php
return array(
    'mailAdress' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 50,'encoding'=>'UTF-8')),
array('EmailAddress'),array('Db_RecordExistsTTM', array('field' => 'user_id', 'table' => 'm_user'))
),
    'password' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('Alnumus'),
array('StringLength', array('min' => 4, 'max' => 20)),
),
    'remember' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True
)
);
?>

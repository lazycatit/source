<?php
$validate = array(
     '*' => array(
Zynas_Filter_Input::ALLOW_EMPTY => true,
),
);

if (isset($_REQUEST['control_number'])) {
    $validate['control_number'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('StringLength', array('max'=>'10','encoding'=>'UTF-8')),
    array('ControlNumber'),
    );
}

return $validate;
?>
<?php
$validate = array(


     '*' => array(
	Zynas_Filter_Input::ALLOW_EMPTY => true,
	),
);
if (isset($_REQUEST['create_from']) && isset($_REQUEST['create_to'])) {
    $validate['create_to'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    'DateTimePeriod',
    Zynas_Filter_Input::FIELDS => array('create_from', 'create_to'),
    Zynas_Filter_Input::MESSAGES => array(0 => '生年月日（開始）より後の日付にしてください。')
    );
}

if (isset($_REQUEST['create_from'])) {
    $validate['create_from'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('DateTimeAll'),
    );
}
if (isset($_REQUEST['create_to'])) {
    $validate['create_to'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,

    array('DateTimeAll'),
    );
}
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
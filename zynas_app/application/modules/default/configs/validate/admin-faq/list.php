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
    Zynas_Filter_Input::FIELDS => array('create_from', 'create_to'),
    'DateTimePeriod',
    Zynas_Filter_Input::MESSAGES => array(0 => '生年月日（開始）より後の日付にしてください。')
    );
}
if (isset($_REQUEST['create_from'])) {
    $validate['create_from'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('DateAll'),
    );
}
if (isset($_REQUEST['create_to'])) {
    $validate['create_to'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('DateAll'),
    );
}
if (isset($_REQUEST['title'])) {
    $validate['title'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('StringLength', array('max'=>'50','encoding'=>'UTF-8'))
    );
}

return $validate;
?>
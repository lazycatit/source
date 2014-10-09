<?php
$validate = array(
     '*' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
),
);

if (isset($_REQUEST['create_from']) && isset($_REQUEST['create_to'])) {
    $validate['create_to'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    Zynas_Filter_Input::FIELDS => array('create_from', 'create_to'),
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
if (isset($_REQUEST['product'])) {
    $validate['product'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('StringLength', array('max'=>'120','encoding'=>'UTF-8')),
    );
}
if (isset($_REQUEST['category'])) {
    $validate['category'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    );
}
if (isset($_REQUEST['order_number'])) {
    $validate['order_number'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => True,
    array('StringLength', array('max'=>'255','encoding'=>'UTF-8')),
    );
}

return $validate;
?>

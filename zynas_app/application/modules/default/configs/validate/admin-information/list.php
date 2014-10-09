<?php
$validate = array(
     '*' => array(
Zynas_Filter_Input::ALLOW_EMPTY => true,
),
);

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
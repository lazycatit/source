<?php
$validate = array(
     '*' => array(
Zynas_Filter_Input::ALLOW_EMPTY => True,
),

);
if (isset($_REQUEST['keysearch'])) {
    $validate['keysearch'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => True,
    array('StringLength', array('max'=>'50','encoding'=>'UTF-8')),
    );
}
return $validate;
?>

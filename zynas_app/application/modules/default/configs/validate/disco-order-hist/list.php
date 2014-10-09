<?php
return array(
    'order_number' => array(
        Zynas_Filter_Input::ALLOW_EMPTY => True,         
        array('StringLength', array('max' => '255','encoding'=>'UTF-8')),
    ),
    'product' => array(
        Zynas_Filter_Input::ALLOW_EMPTY => True,         
        array('StringLength', array('max' => '120','encoding'=>'UTF-8')),
    )
);
?>
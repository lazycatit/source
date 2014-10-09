<?php
return array(
        'token' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
),
		'title' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max'=>'50','encoding'=>'UTF-8')),
),
		'question' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
),
		'answer' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
),
        'id' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
),
);
?>

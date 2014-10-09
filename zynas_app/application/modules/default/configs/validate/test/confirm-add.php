<?php
return array(
    'token' => array(Zynas_Filter_Input::ALLOW_EMPTY => False,),
    'name' => array(Zynas_Filter_Input::ALLOW_EMPTY => False,array('StringLength', array('max'=>'150','encoding'=>'UTF-8'))),
	'rbt_gender' => array(),
	'birthday' => array(),
	'option' => array(),
);
?>

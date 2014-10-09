<?php
return array(
	'id' => array(Zynas_Filter_Input::ALLOW_EMPTY => False,array('Db_RecordExists', array('field' => 'id', 'table' => 't_test'))),
    'token' => array(Zynas_Filter_Input::ALLOW_EMPTY => False,),
    'name' => array(Zynas_Filter_Input::ALLOW_EMPTY => False,array('StringLength', array('max'=>'150','encoding'=>'UTF-8'))),
	'rbt_gender' => array(),
	'birthday' => array(),
	'option' => array(),
);
?>

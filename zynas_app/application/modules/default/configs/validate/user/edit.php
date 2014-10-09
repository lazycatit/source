<?php
return array(
    'id' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('Db_RecordExists', array('field' => 'id', 'table' => 'm_user')),
)
);
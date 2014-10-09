<?php
return array(
        'mailAddress' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('EmailAddress'),
array('StringLength', array('max'=>50,'encoding'=>'UTF-8')),
),
);
?>
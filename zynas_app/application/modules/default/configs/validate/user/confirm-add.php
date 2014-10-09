<?php
return array(
    'mail_adress' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 255)),
array('EmailAddress'),
array('Db_NoRecordExists', array('field' => 'mail_adress', 'table' => 'fw_m_user')),
),
    'password' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('Alnumus'),
array('StringLength', array('min' => 4, 'max' => 255)),
),
    'unit_id' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False
),
    'first_name' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 255)),
array('Zenkaku'),
),
    'last_name' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 255)),
array('Zenkaku'),
),
    'first_kana' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 255)),
array('ZenkakuKatakana'),
),
    'last_kana' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('StringLength', array('max' => 255)),
array('ZenkakuKatakana'),
),
    'gender' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False
),
    'birth' => array(
Zynas_Filter_Input::ALLOW_EMPTY => False,
array('BirthdayYmd'),
),
);
?>

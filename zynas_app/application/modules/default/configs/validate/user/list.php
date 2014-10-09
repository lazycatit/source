<?php
$validate = array(
     '*' => array(
Zynas_Filter_Input::ALLOW_EMPTY => true,
),
);
if (isset($_REQUEST['birth_from'])) {
    $validate['birth_from'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('BirthdayYmd'),
    );
}
if (isset($_REQUEST['birth_from_to'])) {
    $validate['birth_from_to'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
    array('BirthdayYmd'),
    );
}
if (isset($_REQUEST['birth_from']) && isset($_REQUEST['birth_from_to'])) {
    $validate['birth_from_to'] =
    array(
    Zynas_Filter_Input::ALLOW_EMPTY => true,
            'DateTimePeriod',
    Zynas_Filter_Input::FIELDS => array('birth_from', 'birth_from_to'),
    Zynas_Filter_Input::MESSAGES => array(0 => '生年月日（開始）より後の日付にしてください。')
    );
}
return $validate;
?>
<?php

class Validate_DateTimePeriod extends Zend_Validate_Abstract {
    const dateTimeFormatError = 'dateTimeFormatError';
    const InvalidPeriod = 'invalidPeriod';

    protected $_messageTemplates = array(
    self::InvalidPeriod => '正しい期間を入力してください。',
    // 日付チェックは、各項目ごとに行うため、
    // エラーメッセージを上のメッセージに統一します
    // ※同じ意味のメッセージが２重に表示されるのを防ぐ
    //         self::dateTimeFormatError => '正しい日時入力してください。'
    );

    public function isValid($values) {
        $values = array_values($values);
        $start = isset($values[0]) ? $values[0] : '';
        $end = isset($values[1]) ? $values[1] : '';
        if (!empty($start) && !strtotime($start)) {
            $this->_error(self::InvalidPeriod);
            return false;
        }
        if (!empty($end) && !strtotime($end)) {
            $this->_error(self::InvalidPeriod);
            return false;
        }
        // 日時フォーマット：YYYY-MM-DD H:i:s
        if (!empty($start)) {
            $startStrings = explode(' ' , $start);
            $startDate = explode('-', $startStrings[0]);
            if (count($startDate) != 3 || !checkdate($startDate[1], $startDate[2], $startDate[0])) {
                $this->_error(self::InvalidPeriod);
                return false;
            }
        }
        if (!empty($end)) {
            $endStrings = explode(' ' , $end);
            $endDate = explode('-', $endStrings[0]);
            if (count($endDate) != 3 || !checkdate($endDate[1], $endDate[2], $endDate[0])) {
                $this->_error(self::InvalidPeriod);
                return false;
            }
        }
        if (!empty($start) && !empty($end) && strtotime($start) > strtotime($end)) {
            $this->_error(self::InvalidPeriod);
            return false;
        }
        return true;
    }
}
?>
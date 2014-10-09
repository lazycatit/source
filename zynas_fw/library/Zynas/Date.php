<?php

class Zynas_Date extends Zend_Date {

    public static function dbDatetime() {
        return date('Y-m-d H:i:s');
    }

    public static function dbDate() {
        return date('Y-m-d');
    }

    public static function dbTime() {
        return date('H:i:s');
    }

    public static function getYearSelectOptions($nullKey = null, $nullValue = null, $minYear = null, $maxYear = null) {
        $options = array();
        if (!is_null($nullKey) && !is_null($nullValue)) $options[$nullKey] = $nullValue;
        $currentYear = date('Y');
        if (is_null($minYear)) $minYear = $currentYear - 100;
        if (is_null($maxYear)) $maxYear = $currentYear;
        for ($i = $minYear; $i <= $maxYear; $i++) $options[$i] = $i;
        return $options;
    }

    public static function getMonthOptions($nullKey = null, $nullValue = null) {
        $options = array();
        for ($i = 1; $i < 13; $i++) {
            $v = sprintf("%02d", $i);
            $options[$v] = $v;
        }
        return is_null($nullKey) ? $options : array($nullKey => $nullValue) + $options;
    }

    public static function getDayOptions($nullKey = null, $nullValue = null) {
        $options = array();
        for ($i = 1; $i < 32; $i++) {
            $v = sprintf("%02d", $i);
            $options[$v] = $v;
        }
        return is_null($nullKey) ? $options : array($nullKey => $nullValue) + $options;
    }

    public static function getMonthSelectOptions($nullKey = null, $nullValue = null, $captions = null) {
        $options = array();
        if (!is_null($nullKey) && !is_null($nullValue)) $options[$nullKey] = $nullValue;
        if (is_null($captions)) {
            $captions = array(
                1 => '1',
                2 => '2',
                3 => '3',
                4 => '4',
                5 => '5',
                6 => '6',
                7 => '7',
                8 => '8',
                9 => '9',
                10 => '10',
                11 => '11',
                12 => '12'
            );
        }
        foreach ($captions as $k => $v) $options[$k] = $v;
        return $options;
    }

    public static function getDaySelectOptions($nullKey = null, $nullValue = null, $maxDay = 31) {
        $options = array();
        if (!is_null($nullKey) && !is_null($nullValue)) $options[$nullKey] = $nullValue;
        for ($i = 1; $i <= $maxDay; $i++) $options[$i] = $i;
        return $options;
    }

    public static function getHourSelectOptions($nullKey = null, $nullValue = null) {
        $options = array();
        if (!is_null($nullKey) && !is_null($nullValue)) $options[$nullKey] = $nullValue;
        for ($i = 0; $i < 24; $i++) $options[$i] = $i < 10 ? '0' . $i : $i;
        return $options;
    }

    public static function getMinuteSelectOptions($nullKey = null, $nullValue = null) {
        $options = array();
        if (!is_null($nullKey) && !is_null($nullValue)) $options[$nullKey] = $nullValue;
        for ($i = 0; $i < 60; $i++) $options[$i] = $i < 10 ? '0' . $i : $i;
        return $options;
    }

    public function toDbDate() {
        return $this->toString('yyyy-MM-dd');
    }

}

?>
<?php

class View_Helper_FormDatePicker extends Zynas_View_Helper {

    public function formDatePicker($name, $default = null, $config = array()) {
        $html = '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $default . '" class="date-picker-select">';

        $option = '';
        if (array_key_exists('minutes_interval', $config)) {
            $option .= 'minutes: {interval:' . $config['minutes_interval'] . '}';
        }
        if (array_key_exists('is_disp_time', $config)) {
            if ($option != '') $option .= ',';
            $option .= 'isDispTime:' . ($config['is_disp_time'] ? 'true' : 'false');
        }
        if (array_key_exists('year', $config)) {
            if ($option != '') $option .= ',';
            $option .= 'year:{top:' . $config['year']['top'] . ', bottom:' . $config['year']['bottom'] . '}';
        }

        // z-indexのCSSはデザインの問題回避用
        $html .= '
        <style type="text/css">
            .ui-datepicker {z-index: 99;}
        </style>
        <script type="text/javascript">
        $(function() {
            if ($(\'input#' . $name . '\').size() > 0) {
                $(\'input#' . $name . '\').datePickerSelect({
                    buttonImage: "/resources/scripts/jquery.date-picker-select/images/calendar.png",
                    clearImage: "/resources/scripts/jquery.date-picker-select/images/delete.png",
                    ' . $option . '
                })
            }
        });
        </script>';

        return $html;
    }

}

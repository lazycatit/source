<?php

class View_Helper_FormDate extends Zynas_View_Helper {

    const DATETIME = 'datetime';
    const DATE = 'date';
    const TEXT = 'text';
    const SELECT = 'select';
    const NULL_KEY = '';
    const NULL_KEY_YEAR = '';
    const NULL_KEY_SECOND = '00';
    const NULL_VALUE = '--';

    protected $className;
    protected $formStyle;
    protected $dateStyle;
    protected $year;
    protected $month;
    protected $day;
    protected $hour;
    protected $minute;
    protected $second;

    public function __construct() {
        $this->formStyle = self::SELECT;
        $this->dateStyle = self::DATE;
    }

    public function formDate($name, $default = null, $config = array()) {

        if (array_key_exists('className', $config)) $this->className = $config['className'];
        if (array_key_exists('formStyle', $config)) $this->formStyle = $config['formStyle'];
        if (array_key_exists('dateStyle', $config)) $this->dateStyle = $config['dateStyle'];

        $defaultInput = explode(' ', $default);
        $defaultDate = explode('-', $defaultInput[0]);
        if (count($defaultDate) != 3) $defaultDate = array(self::NULL_KEY_YEAR, self::NULL_KEY, self::NULL_KEY);
        $defaultTime = isset($defaultInput[1]) ? explode(':', $defaultInput[1]) : array();
        if (count($defaultTime) != 3) $defaultTime = array(self::NULL_KEY, self::NULL_KEY, self::NULL_KEY_SECOND);

        list($this->year, $this->month, $this->day) = $defaultDate;
        list($this->hour, $this->minute, $this->second) = $defaultTime;
        if ($this->dateStyle == self::DATETIME) {
            $defaultValue = $default ? $this->year . '-' . $this->month . '-' . $this->day . ' ' . $this->hour . ':' . $this->minute . ':' . $this->second : false;
        }else{
            $defaultValue = $default ? $this->year . '-' . $this->month . '-' . $this->day . ' ': false;
        }

        $html = '<span>';
        $parseInt = false;
        switch ($this->formStyle) {
            case self::TEXT:
                $html .= $this->setText($name);
                break;
            case self::SELECT:
                $sort = true;
                if(isset($config['sortFlg'])) {
                    $sort = false;
                }

                $html .= $this->setSelect($name, $sort);
                $parseInt = true;
                break;
        }

        $html .= '<input type = "hidden" id = "' . $name .'" name = "' . $name . '" value = "' . $defaultValue . '">';
        $html .= '</span>';

        $html .= '<script type="text/javascript">
                        $(function() {
                            $("#' . $name . '").change(function(){
                                $("#' . $name . '_year").val($("#' . $name . '").val().substr(0,4));
                                $("#' . $name . '_month").val(' . ($parseInt ? 'parseInt(' : '') . '$("#' . $name . '").val().substr(5,2)' . ($parseInt ? ', 10)' : '') . ');
                                $("#' . $name . '_day").val(' . ($parseInt ? 'parseInt(' : '') . '$("#' . $name . '").val().substr(8,2)' . ($parseInt ? ', 10)' : '') . ');
                                ';
        if ($this->dateStyle == self::DATETIME) {
            $html .= '$("#' . $name . '_hour").val(\'00\');
                                    $("#' . $name . '_minute").val(\'00\');';
        }

        $html .=       '
                            });
                            $("#' . $name . '_year").change(dateChange_' . $name . ');
                            $("#' . $name . '_month").change(dateChange_' . $name . ');
                            $("#' . $name . '_day").change(dateChange_' . $name . ');';
        if ($this->dateStyle == self::DATETIME) {
          $html .=               '$("#' . $name . '_hour").change(dateChange_' . $name . ');
                        $("#' . $name . '_minute").change(dateChange_' . $name . ');';
        }
                            
        $html .=      '});

                        function dateChange_' . $name . '() {
                            var setDate = \'\';
                            var setTime = \'\';
                            if(!$("#' . $name . '_year").val() && !$("#' . $name . '_month").val() && !$("#' . $name . '_day").val()){
                            
                            }
                            else{
                            setDate = $("#' . $name . '_year").val() + \'-\' + $("#' . $name . '_month").val() + \'-\' + $("#' . $name . '_day").val();
                            }
                            ';
        if ($this->dateStyle == self::DATETIME) {
            $html .= 'if ($("#' . $name . '_year").val() && $("#' . $name . '_month").val() && $("#' . $name . '_day").val()) {
                                            setTime = \' \' + $("#' . $name . '_hour").val() + \':\' + $("#' . $name . '_minute").val() + \':00\';
                                          }';
        }

        //$html .= 'setTime = \' 00:00:00\'';
        $html .= '
                            $("#' . $name . '").val(setDate + setTime);
                        }

                        function dateDelete_' . $name . '() {
                            $("#' . $name . '_year").val(\'\');
                            $("#' . $name . '_month").val(\'\');
                            $("#' . $name . '_day").val(\'\');';
        if ($this->dateStyle == self::DATETIME) {
            $html .= '
                                    $("#' . $name . '_hour").val(\'\');
                                    $("#' . $name . '_minute").val(\'\');
                                ';
        }
        $html .= '
                            dateChange_' . $name . '();
                        }
        	            </script>
                        ';

        // 日付のクリア（現在は非表示）
        //                 $html .= '<a href="javascript:dateDelete_' . $name . '();">[×]</a>';

        return $html;
    }

    private function setText($name) {
        $className = is_null($this->className)? '': ' class="' . $this->className . '"';
        $html = '';
        $html .= '<input type="text" id="' . $name .'_year" name = "' . $name . '_year" value = "' . $this->year . '"' . $className . ' size="4">&nbsp;年';
        $html .= '<input type="text" id="' . $name .'_month" name = "' . $name . '_month" value = "' . $this->month . '"' . $className . ' size="2">&nbsp;月';
        $html .= '<input type="text" id="' . $name .'_day" name = "' . $name . '_day" value = "' . $this->day . '"' . $className . ' size="2">&nbsp;日';
        if ($this->dateStyle == self::DATETIME) {
            $html .= '<input type="text" id="' . $name .'_hour" name = "' . $name . '_hour" value = "' . $this->hour . '"' . $className . ' size="2">&nbsp;時';
            $html .= '<input type="text" id="' . $name .'_minute" name = "' . $name . '_minute" value = "' . $this->minute . '"' . $className . ' size="2">&nbsp;分';
        }
        return $html;
    }

    private function setSelect($name, $sort) {
        $className = is_null($this->className)? '': ' class="' . $this->className . '"';
        $html = '';
        $html .= '<select id="' . $name .'_year" name = "' . $name . '_year"' . $className . '>&nbsp;';

        if($sort) {
            foreach (Zynas_Date::getYearSelectOptions() as $k => $v) {
                $option[] = '<option value="' . $k . '"' . ($this->year == $k ? ' selected="selected"' : '') . '>' . $v . '</option>';
            }
        }
        else {            
            foreach (Zynas_Date::getYearSelectOptions(self::NULL_KEY, '') as $k => $v) {
                $html .= '<option value="' . $k . '"' . ($this->year == $k ? ' selected="selected"' : '') . '>' . $v . '</option>';
            }
        }

        if($sort) {
            $tempOption = array_reverse($option);

            $selected = null;

            if($this->year == '') {
                $selected = ' selected="selected"';
            }

//            $html .= '<option value=""'. $selected .'></option>';
            $html .= '<option value=""'. $selected .'>'. self::NULL_VALUE .'</option>';

            foreach ($tempOption as $v) {
                $html .= $v;
            }
        }

        $html .= '</select>&nbsp;年';

        $html .= '<select id="' . $name .'_month" name = "' . $name . '_month"' . $className . '>&nbsp;';
        foreach (Zynas_Date::getMonthSelectOptions(self::NULL_KEY, self::NULL_VALUE) as $k => $v) {
            $html .= '<option value="' . $k . '"' . ($this->month == $k ? ' selected="selected"' : '') . '>' . $v . '</option>';
        }
        $html .= '</select>&nbsp;月';

        $html .= '<select id="' . $name .'_day" name = "' . $name . '_day"' . $className . '>&nbsp;';
        foreach (Zynas_Date::getDaySelectOptions(self::NULL_KEY, self::NULL_VALUE) as $k => $v) {
            $html .= '<option value="' . $k . '"' . ($this->day == $k ? ' selected="selected"' : '') . '>' . $v . '</option>';
        }
        $html .= '</select>&nbsp;日';

        if ($this->dateStyle == self::DATETIME) {
            $html .= '<select id="' . $name .'_hour" name = "' . $name . '_hour"' . $className . '>';
            foreach (Zynas_Date::getHourSelectOptions(self::NULL_KEY, self::NULL_VALUE) as $k => $v) {
                $html .= '<option value="' . $k . '"' . ((is_numeric($this->hour) && $this->hour == $k) ? ' selected="selected"' : '') . '>' . $v . '</option>';
            }
            $html .= '</select>&nbsp;時';
            $html .= '<select id="' . $name .'_minute" name = "' . $name . '_minute"' . $className . '>';
            foreach (Zynas_Date::getMinuteSelectOptions(self::NULL_KEY, self::NULL_VALUE) as $k => $v) {
                $html .= '<option value="' . $k . '"' . ((is_numeric($this->minute) && $this->minute == $k) ? ' selected="selected"' : '') . '>' . $v . '</option>';
            }
            $html .= '</select>&nbsp;分';
        }
        return $html;

    }

}

?>
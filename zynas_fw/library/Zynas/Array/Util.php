<?php

class Zynas_Array_Util {

    public static function extract($array, $value, $key = null) {
        $result = array();
        if (!is_array($array)) return $result;
        if (!empty($key)) {
            foreach ($array as $item) {
                if (isset($item[$key]) && isset($item[$value])) $result[$item[$key]] = $item[$value];
            }
        }
        else {
            foreach ($array as $item) {
                if (isset($item[$value])) array_push($result, $item[$value]);
            }
        }
        return $result;
    }

}

?>

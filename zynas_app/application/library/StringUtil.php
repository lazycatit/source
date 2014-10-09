<?php
class StringUtil {


    /*
     * return sub String
     * If $str > $len  -> return "$len..."
     * else return $len;
     * */

    static function getViewSubString($str_,$len){
        $str = trim($str_);

        if ($str_ != "") {
            if (mb_strlen($str_,'UTF-8')  <= $len) {
                $str = $str_;
            } else {
                $str = mb_substr($str_, 0, $len,'UTF-8').'....';
            }
        }
        return $str;
    }
}
?>
<?php

class Zynas_Session_Util {
    public static function decode($data) {
        $buff = preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/', $data, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $decoded = array();
        $iMax = count($buff);
        for ($i = 0; $i < $iMax; $i++) {
            $decoded[$buff[$i++]] = unserialize($buff[$i]);
        }
        return $decoded;
    }
}

?>

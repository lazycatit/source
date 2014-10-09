<?php

function d($var, $dieAfter = false) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if ($dieAfter) die();
}

function h($var) {
    return htmlspecialchars($var, ENT_QUOTES);
}

function pjoin() {
    $args = func_get_args();
    $paths = array();
    foreach ($args as $a) {
        $buff = explode('/', $a);
        foreach ($buff as $d) {
            if (!empty($d)) {
                $paths[] = $d;
            }
        }
    }
    return implode('/', $paths);
}

if(!function_exists('get_called_class')) {
    class __Future {
        static $i = 0;
        static $fl = null;
        static function get_called_class() {
            $bt = debug_backtrace();
            if(self::$fl == $bt[2]['file'].$bt[2]['line']) {
                self::$i++;
            } else {
                self::$i = 0;
                self::$fl = $bt[2]['file'].$bt[2]['line'];
            }
            $lines = file($bt[2]['file']);
            preg_match_all('
                /([a-zA-Z0-9\_]+)::'.$bt[2]['function'].'/',
                $lines[$bt[2]['line']-1],
                $matches
            );
            return $matches[1][self::$i];
        }
    }
    function get_called_class() {
        return __Future::get_called_class();
    }
}

?>
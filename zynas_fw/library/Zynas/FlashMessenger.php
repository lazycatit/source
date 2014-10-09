<?php

class Zynas_FlashMessenger {

    const NSPACE = 'FlashMessenger';
    const KEY_SUCCESS = 'success';
    const KEY_ERROR = 'error';
    const KEY_NOTICE = 'notice';

    private function __construct() {}

    private static function _prepare() {
        if (!isset($_SESSION[self::NSPACE])) {
            $_SESSION[self::NSPACE] = array(
                self::KEY_SUCCESS => array(),
                self::KEY_ERROR => array(),
                self::KEY_NOTICE => array()
            );
        }
    }

    private static function _clean() {
        self::_prepare();
        if (count($_SESSION[self::NSPACE][self::KEY_SUCCESS]) == 0 &&
            count($_SESSION[self::NSPACE][self::KEY_ERROR]) == 0 &&
            count($_SESSION[self::NSPACE][self::KEY_NOTICE]) == 0) {
            unset($_SESSION[self::NSPACE]);
        }
    }

    public static function addSuccess($message) {
        return self::_add(self::KEY_SUCCESS, $message);
    }

    public static function addError($message) {
        return self::_add(self::KEY_ERROR, $message);
    }

    public static function addNotice($message) {
        return self::_add(self::KEY_NOTICE, $message);
    }

    private static function _add($key, $message) {
        self::_prepare();
        $_SESSION[self::NSPACE][$key][] = $message;
    }

    public static function dumpSuccess() {
        return self::_dump(self::KEY_SUCCESS);
    }

    public static function dumpError() {
        return self::_dump(self::KEY_ERROR);
    }

    public static function dumpNotice() {
        return self::_dump(self::KEY_NOTICE);
    }

    private static function _dump($key) {
        self::_prepare();
        $messages = $_SESSION[self::NSPACE][$key];
        $_SESSION[self::NSPACE][$key] = array();
        self::_clean();
        return $messages;
    }

    public static function dumpMessages() {
        self::_prepare();
        $messages = $_SESSION[self::NSPACE];
        unset($_SESSION[self::NSPACE]);
        return $messages;
    }

    public static function getMessages() {
        self::_prepare();
        $messages = $_SESSION[self::NSPACE];
        return $messages;
    }

    public static function toString(array $array){
        $text = "";
        foreach( $array as $key => $val ){
          if(is_array($val)){
            $text .= self::toString($val) . "|";
          }else{
            $text .= $val . "|";
          }
        }
        return rtrim($text, "|");
    }
}

?>
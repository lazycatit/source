<?php

class Zynas_Cookie {

    public static function get($key = null) {
        return ($key) ? $_COOKIE[$key] : $_COOKIE;
    }

    public static function set(array $values, $expiryInDays = 30, $path = '/') {
        foreach($values as $k => $v) setcookie($k, $v, ($expiryInDays * 86400) + time(), $path);
    }

    public static function destory($path = '/') {
        if(!empty($_COOKIE)) foreach($_COOKIE as $k => $v) if($k != 'PHPSESSID' && $k != session_name()) setcookie($k, NULL, time() - 3600, $path);
        return true;
    }

    public static function exists($key = null) {
        return (bool) (!empty($key)) ? isset($_COOKIE[$key]) : isset($_COOKIE);
    }

    public static function isExpired($key = null) {
        $idTime = explode(".", ($key) ? self::get($key) : self::get(Zynas_Registry::getConfig()->cookie->timeKey));
        return (bool) (time() > $idTime[1]) ? false : self::destory();
    }

    public static function isValid($key, $effectiveDays = 30) {
        $effectiveDate = date("Ymd", mktime(0, 0, 0, date('m'), date('d') - $effectiveDays, date('Y')));
        if (!empty($_COOKIE[$key]) && $_COOKIE[$key] > $effectiveDate) return true;
        return false;
    }

    public static function delete($key, $path = '/') {
        if (self::exists($key)) setcookie($key, null, 0, $path);
    }

}

?>

<?php

class Zynas_CookieManager extends Zynas_Cookie {

    const NSPACE = 'CM';
    const CONNECTOR = '_';

    private $sslAvailable = false;

    public function __construct(){
        if (Zynas_Registry::getConfig()->system->ssl->available) {
            $this->sslAvailable = true;
        }
    }

    public function setData($data, $key, $userId = '', $expiryInDays = 30, $secure = false) {
        if($secure && !$this->sslAvailable){
            $secure = false;
        }
        $uniqueKey = $this->createUniqueKey($key, $userId);
        $values = array($uniqueKey => $data);
        self::set($values, $expiryInDays, '/', $secure);
    }

    public function getData($key, $userId = '') {
        $uniqueKey = $this->createUniqueKey($key, $userId);
        $data = null;
        if (self::exists($uniqueKey)) {
            $data = self::get($uniqueKey);
        }
        return $data;
    }

    public function removeData($key, $userId = '', $secure = false) {
        if($secure && !$this->sslAvailable){
            $secure = false;
        }
        $uniqueKey = $this->createUniqueKey($key, $userId);
        self::delete($uniqueKey, '/', $secure);
    }

    protected function createUniqueKey($key, $userId) {
        $userKey = '';
        if (!Zynas_String::isEmpty($userId)) {
            $userKey = self::CONNECTOR . $userId;
        }
        return self::NSPACE . $userKey . self::CONNECTOR . $key;
    }

    public static function set(array $values, $expiryInDays = 30, $path = '/', $secure = false) {
        foreach($values as $k => $v) setcookie($k, $v, ($expiryInDays * 86400) + time(), $path, null, $secure);
    }

    public static function delete($key, $path = '/', $secure = false) {
        if (self::exists($key)) setcookie($key, null, time() - 3600, $path, null, $secure);
    }
}
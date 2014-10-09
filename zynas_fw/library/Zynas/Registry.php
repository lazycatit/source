<?php

class Zynas_Registry extends Zend_Registry {

    const KEY_MASTER_DB = 'masterDb';
    const KEY_SLAVE_DB = 'slaveDb';
    const KEY_CONFIG = 'config';

    // !!! side: After PHP 5.3~, use __callStatic()

    public static function getMasterDb() {
        return self::get(self::KEY_MASTER_DB);
    }

    public static function setMasterDb(Zend_Db_Adapter_Pdo_Abstract $db) {
        Zynas_Registry::set(self::KEY_MASTER_DB, $db);
    }

    public static function getSlaveDb() {
        return self::get(self::KEY_SLAVE_DB);
    }

    public static function setSlaveDb(Zend_Db_Adapter_Pdo_Abstract $db) {
        Zynas_Registry::set(self::KEY_SLAVE_DB, $db);
    }

    public static function getConfig() {
        return self::get(self::KEY_CONFIG);
    }

    public static function setConfig(Zend_Config $config) {
        Zynas_Registry::set(self::KEY_CONFIG, $config);
    }

}

?>

<?php

abstract class Zynas_Db_Table extends Zend_Db_Table_Abstract {

    protected $_rowClass = 'Zynas_Db_Table_Row';
    protected $_rowsetClass = 'Zynas_Db_Table_Rowset';
    protected $_primary = 'id';

    protected static $_instances = array();
    protected static $_prefix = null;

    protected function _setupTableName() {
        if (empty($this->_name)) $this->_name = self::$_prefix . Zynas_Inflector::underscore(substr(get_class($this), 0, -1));
        parent::_setupTableName();
    }

    public static function getInstance() {
        $class = get_called_class();
        if (!array_key_exists($class, self::$_instances)) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }

    public static function getSelectOptions($nullKey = null, $nullValue = null, $keyField = 'id', $valueField = 'caption') {
        $class = get_called_class();
        $table = new $class();
        $select = $table->select()
                        ->from($table, array($keyField, $valueField));
        if ($table->hasField('deleted')) $select->where("deleted IS NULL");
        if ($table->hasField('sort_order')) $select->order("sort_order");
        $selectOptions = array();
        if (!is_null($nullKey)) $selectOptions[$nullKey] = $nullValue;
        return empty($selectOptions) ? $table->fetchAll($select)->toKeyValuePairs($keyField, $valueField) : $selectOptions + $table->fetchAll($select)->toKeyValuePairs($keyField, $valueField);
    }

    public static function getSelectOption($key, $keyField = 'id', $valueField = 'caption') {
        $buff = self::getSelectOptions(null, null, $keyField, $valueField);
        return array_key_exists($key, $buff) ? $buff[$key] : null;
    }

    public static function setPrefix($prefix) {
        self::$_prefix = $prefix;
    }

    public static function getPrefix() {
        return self::$_prefix;
    }

    public function hasField($name) {
        return in_array($name, $this->_cols);
    }

}

?>
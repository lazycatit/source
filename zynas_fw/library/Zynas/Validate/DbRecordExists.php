<?php

class Zynas_Validate_DbRecordExists extends Zend_Validate_Abstract {

    const ALREADY_EXISTS = 'alreadyExists';
    const NOT_FOUND_TABLE_CLASS = 'notFoundTableClass';
    const NOT_FOUND_COLUMN_NAME = 'notFoundColumnName';

    protected $_messageTemplates = array(
        self::ALREADY_EXISTS => "%caption%は既に登録されています",
        self::NOT_FOUND_TABLE_CLASS => "%table%は存在しません",
        self::NOT_FOUND_COLUMN_NAME => "%column%は存在しません"
    );

    protected $_messageVariables = array(
        'table' => '_tableClass',
        'column' => '_columnName',
        'caption' => '_caption'
    );

    protected $_tableClass = '';
    protected $_columnName = '';
    protected $_caption = '';
    protected $_excludeCondition = array();

    public function __construct($tableClass, $columnName, $caption = null, $excludeCondition = array()) {
        $this->_tableClass = $tableClass;
        $this->_columnName = $columnName;
        $this->_excludeCondition = (array) $excludeCondition;
        $this->_caption = !is_null($caption) ? $caption : $columnName;
    }

    public function isValid($value) {
        $table = new $this->_tableClass();
        if (!is_subclass_of($table, 'Zynas_Db_Table')) {
            $this->_error(self::NOT_FOUND_TABLE_CLASS);
            return false;
        }
        try {
            $select = $table->select()
                            ->from($table, array('num' => new Zynas_Db_Expr("COUNT(*)")))
                            ->where($this->_columnName . ' = ?', $value);
            if (count($this->_excludeCondition) > 0) foreach ($this->_excludeCondition as $cond) $select->where($cond[0], $cond[1]);
            if ($table->fetchRow($select)->num > 0) {
                $this->_error(self::ALREADY_EXISTS);
                return false;
            }
        }
        catch (Exception $e) {
            $this->_error(self::NOT_FOUND_COLUMN_NAME);
            return false;
        }

        return true;
    }

}

?>

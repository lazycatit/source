<?php

class Zynas_Db_Table_Row extends Zend_Db_Table_Row_Abstract {

    public function __toString() {
        return (string) ($this->getTable()->hasField('caption') ? $this->caption . ' ' : '') . '(ID: ' . $this->id . ')';
    }

}

?>
<?php

class Zynas_Db_Table_Rowset extends Zend_Db_Table_Rowset_Abstract {

    public function toKeyValuePairs($keyField = 'id', $valueField = 'caption') {
        $kvPairs = array();
        foreach ($this as $row) {
            $kvPairs[$row->{$keyField}] = $row->{$valueField};
        }
        return $kvPairs;
    }

    public function toFlatArray($key = null) {
        $flatArray = array();
        foreach ($this as $row) {
            $flatArray[] = $key ? $row->{$key} : current($row->toArray());
        }
        return $flatArray;
    }

}

?>
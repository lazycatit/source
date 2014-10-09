<?php

class Db_Table_Row extends Zynas_Db_Table_Row {

    public function __toString() {
        return (string) $this->getTable()->hasField('caption') ? $this->caption . ' (ID: ' . $this->id . ')' : $this->id;
    }

}

?>

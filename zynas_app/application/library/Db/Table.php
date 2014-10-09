<?php

class Db_Table extends Zynas_Db_Table {
    
    const DELETE_FLG_ON = 'D';
    const FROM_HMS = '00:00:00';
    const TO_HMS = '23:59:59';
    const SPACE = ' ';
    const COMMA = ',';
    const RESULT_MATCHED = '1';
    const RESULT_UNMATCHED = '2';
    const RESULT_OUTSIDE_LAW = '3';
    
    /**
     * エイリアス名付きのカラム名配列を作成します
     * @param string $alias エイリアス名
     * @param array $cols カラム名一覧
     * @return array エイリアス名付きカラム名 => カラム名
     */
    public function getAliasClomunNames($alias = null, array $cols = null) {
        if (is_null($alias) || strcmp($alias, '') === 0) $alias = $this->info('name');
        if (is_null($cols) || empty($cols)) $cols = $this->_getCols();

        $aliasColumnNames = array();
        foreach ($cols as $column) {
            $aliasColumnNames[$alias . '_' . $column] = $column;
        }

        return $aliasColumnNames;
    }

    /**
     * idをキーに、論理削除されていないレコードを取得します
     * @param int $id id
     * @return fetchRowに依存。レコードが存在しない場合null
     */
    public function getEntryById($id) {
        $select = $this->getSelectById($id);
        return $this->fetchRow($select);
    }


    /**
     * idをキーに、論理削除されていないレコードを取得するオブジェクトを返却します
     * @param int $id id
     * @return Zend_Db_Table_Select object
     */
    public function getSelectById($id) {
        return $this->select()
        //         ->where('deleted IS NULL')
        ->where('id = ?', $id);
    }

    /**
     * Returns Zend_Db_Table_Select where deleted is null.
     * @param bool $withFromPart
     * @return Zend_Db_Table_Select
     */
    public function selectActive($withFromPart = self::SELECT_WITHOUT_FROM_PART) {
        $select = parent::select($withFromPart);
        return $this->hasField('deleted') ? $select->where('deleted IS NULL') : $select;
    }

    /**
     * Create row for save by admin.
     * @param  array $row
     * @param  int $adminUserId
     * @return Zynas_Db_Table_Row
     */
    public function createRowByAdmin($data, $adminUserId) {
        $row = parent::createRow();
        if ($data instanceof Zynas_Filter_Input) {
            foreach ($this->_cols as $col) if (isset($data->{$col}) && (!empty($data->{$col}) || $data->{$col} === '0')) $row->{$col} = $data->{$col};
        }
        else {
            foreach ($this->_cols as $col) if (isset($data[$col]) && (!empty($data[$col]) || $data[$col] === '0')) $row->{$col} = $data[$col];
        }
        $now = Zynas_Date::dbDatetime();
        if ($this->hasField('created')) $row->created = $now;
        if ($this->hasField('created_by')) $row->created_by = $adminUserId;
        if ($this->hasField('modified')) $row->modified = $now;
        if ($this->hasField('modified_by')) $row->modified_by = $adminUserId;
        return $row;
    }
}

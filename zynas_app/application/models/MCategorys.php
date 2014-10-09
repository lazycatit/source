<?php
class MCategorys extends Db_Table {
    protected $_rowClass = 'MCategory';
    const SELECT_ISSUE_BY_MODEL = 1;
    const SELECT_ISSUE_BY_SERIAL = 2;
    const CATEGORY_DEVICE = '01';
    const CATEGORY_SUBDEVICE = '02';
    const CATEGORY_PWP = '03';
    const SUB_CATEGORY_ZERO = '00';
    const CATEGORY_ZERO = '00';
    const FIELD_PRODUCT_CATEGORY = 'product_category';
    const FIELD_CATEGORY = 'category';
    const FIELD_USER_TYPE = 'user_type';
    const FIELD_CUSTOMER_TYPE = 'customer_type';
    const FIELD_TYPE = 'type';
    const FIELD_RESULT = 'result';

    public function getEntry($mainCategory, $subCategory, $field) {
        $select = $this->select()->from(array('mctg' => $this->info('name')))
        ->where('mctg.field = ?', $field)
        ->where('mctg.sub_category = ?', $subCategory)
        ->where('mctg.main_category = ?', $mainCategory)
        ->where('mctg.delete_flg <> ? OR mctg.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . $select);

        return $this->fetchRow($select);
    }

    public function getMainProductCategory() {
        $select = $this->select()->from(array('mctg' => $this->info('name')), array('mctg.main_category', 'mctg.name1'))
        ->where('mctg.field = ?', self::FIELD_PRODUCT_CATEGORY)
        ->where('mctg.sub_category = ?', self::SUB_CATEGORY_ZERO)
        ->where('mctg.delete_flg <> ? OR mctg.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)->group('mctg.main_category')
        ->order('mctg.main_category ASC');
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . $select);

        return $this->fetchAll($select);
    }

    public function getMainProductCategoryName($mainCategory = null) {
        $select = $this->select()->from(array('mctg' => $this->info('name')), array('mctg.main_category', 'mctg.name1'))
        ->where('mctg.main_category = ?', $mainCategory)
        ->where('mctg.sub_category = ?', self::SUB_CATEGORY_ZERO)
        ->where('mctg.field = ?', self::FIELD_PRODUCT_CATEGORY)
        ->where('mctg.delete_flg <> ? OR mctg.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->group('mctg.main_category')
        ->order('mctg.name1 ASC');
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . $select);

        return $this->fetchRow($select);
    }

    public function getPadSerial() {
        $select = $this->select()->from(array('mctg' => $this->info('name')))
        ->where('mctg.delete_flg <> ? OR mctg.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mctg.main_category = ?', self::CATEGORY_PWP)
        ->where('mctg.field = ?', self::FIELD_CATEGORY)
        ->order('mctg.name1 ASC');
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . $select);

        return $this->fetchAll($select);
    }

    public function getCategoryName2($subCategory = '') {
        $select = $this->select()->from(array('mctg' => $this->info('name')))
        ->where('mctg.sub_category = ?', $subCategory)->where('mctg.sub_category <> ?', self::SUB_CATEGORY_ZERO)
        ->where('mctg.field = ?', self::FIELD_CATEGORY)
        ->where('mctg.delete_flg <> ? OR mctg.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mctg.main_category = ?', self::CATEGORY_PWP);
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . $select);

        return $this->fetchRow($select);
    }

    public function getUserTypeName($userType) {
        $select = $this->select()->from(array('mctg' => $this->info('name')))
        ->where('mctg.sub_category = ?', self::SUB_CATEGORY_ZERO)
        ->where('mctg.main_category = ?', $userType)
        ->where('mctg.field = ?', self::FIELD_USER_TYPE)
        ->where('mctg.delete_flg <> ? OR mctg.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . $select);

        return $this->fetchRow($select);
    }

}
?>

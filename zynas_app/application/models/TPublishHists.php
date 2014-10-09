<?php

class TPublishHists extends Db_Table
{
    protected $_rowClass = 'TPublishHist';
    const FROM_SEC = '00';
    const TO_SEC = '59';
    
    /**
     * 一覧表示用のデータを取得する
     * @param array $where
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getListOpeartionLogSelect($where = array()) {
        $select = $this->select()
        ->from(array('tph' => $this->info('name'))) -> setIntegrityCheck(false)
        ->where('tph.delete_flg <> ? OR tph.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('tph.create_date DESC');

        if (isset($where['control_number']) && strcmp($where['control_number'], '')!==0
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))
            &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))){

            $select->where('tph.control_number like?', '%' . $where['control_number'] . '%');
        }
        if (isset($where['create_from']) && '' !== $where['create_from']
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))) {
                
            $select->where('tph.create_date >= ?', substr($where['create_from'], 0, strlen($where['create_from'])-2) . self::FROM_SEC);
        }
        if (isset($where['create_to']) && '' !== $where['create_to']
            &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))) {
                
            $select->where('tph.create_date <= ?', substr($where['create_to'], 0, strlen($where['create_to'])-2) . self::TO_SEC);
        }
        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.';Select='.$select);    
        return $select;
    }
    
    /**
     * 
     * @param string $control_number
     * @param int $publish_type
     * @return Zend_Db_Table_Row_Abstract
     */
    function getRow($control_number, $publish_type) {
        $select = $this->select()
        ->from(array('tph' => $this->info('name')))
        ->where('tph.delete_flg <> ? or tph.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tph.control_number = ?', $control_number)
        ->where('tph.publish_type = ?', $publish_type);
         
        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.$control_number.';Select='.$select);
        
        return $this->fetchRow($select);
    }
}
   

?>
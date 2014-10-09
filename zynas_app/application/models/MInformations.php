<?php
class MInformations extends Db_Table {
    protected $_rowClass = 'MInformation';
    
    /**
     * 一覧表示用のデータを取得する
     * @param array $where
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getListSelect($where = array()) {
        $select = $this->select()
        ->from(array('minf' => $this->info('name')))
        ->where('minf.delete_flg <> ? OR minf.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('create_date DESC');

        if (isset($where['title']) && (strcmp($where['title'], '') !== 0)
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))
            &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))
            ) {
             //replace all whitespaces with one whitespace
            $title_key = preg_replace('!\s+!', ' ', $where['title']);
            $title_key = explode(Db_Table::SPACE, $title_key);
            $num = count($title_key);
            foreach ($title_key as $key => $value) {
                if (strcmp($num, '1') === 0) {  
                    $select->where('minf.title like ?', '%' . $value . '%');
                } elseif (strcmp($key, '0') === 0) {                    
                    $select->where('(minf.title like ?', '%' . $value . '%');
                } elseif (strcmp($key, $num - 1) === 0){                    
                    $select->orwhere('minf.title like ?)', '%' . $value . '%');
                } else {                    
                    $select->orwhere('minf.title like ?', '%' . $value . '%');
                }
            }  
        }

        if (isset($where['create_from']) && '' !== $where['create_from']
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))) {
                
            $select->where('create_date >= ?', $where['create_from'] . ' ' . Db_Table::FROM_HMS);
        }
        if (isset($where['create_to']) && '' !== $where['create_to']
            &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))) {
                
            $select->where('create_date <= ?', $where['create_to'] . ' ' . Db_Table::TO_HMS);
        }

        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);
        
        return $select;
    }
    
    /**
     * 
     * @param array $where
     * @param int $limit
     */
    public function getLimitList($where = array(), $limit = 10) {
        $select = $this->select()
        ->from(array('minf' => $this->info('name')))
        ->where('minf.delete_flg <> ? OR minf.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('create_date DESC');

        if (isset($where['title']) && (strcmp($where['title'], '') !== 0)) $select->where('minf.title like ?', '%' . $where['title'] . '%');

        if (isset($where['create_from']) && '' !== $where['create_from']) {
            $select->where('create_date >= ?', $where['create_from']);
        }
        if (isset($where['create_to']) && '' !== $where['create_to']) {
            $select->where('create_date <= ?', $where['create_to']);
        }
        $select->limit($limit, 0);
       
        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);

        return $this->fetchAll($select);

    }
    
    /**
     * 
     * @return number
     */
    public function count() {
        $select = $this->select()
        ->from(array('minf' => $this->info('name')), array('num' => 'COUNT(id)'))
        ->where('minf.delete_flg <> ? OR minf.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('create_date DESC');
        
        $result = $this->fetchRow($select);        
        if($result)
        return $result->num;
        else
        return 0;
    }

}
?>
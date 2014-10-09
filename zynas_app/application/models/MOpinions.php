<?php
class MOpinions extends Db_Table {
    
    protected $_rowClass = 'MOpinion';
   
    function getEntries($product_number){
        $select = $this->select()
        ->from(array('mo' => $this->info('name')))
        ->where('mo.product_number  = ?' ,$product_number)
        ->where('mo.delete_flg <> ? or mo.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);        
        
        Log::debugLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);
        
        return $this->fetchAll($select);
    }
}
?>
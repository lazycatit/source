<?php
class MSummaryBeforeOpinions extends Db_Table {

    protected $_rowClass = 'MSummaryBeforeOpinion';
    
    function getEntries($product_number, $serial_number){
        $select = $this->select()
        ->from(array('msbo' => $this->info('name')))
        ->where('msbo.product_number  = ?' ,$product_number)
        ->where('msbo.serial_number  = ?' ,$serial_number)
        ->where('msbo.delete_flg <> ? or msbo.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        
        Log::debugLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);
        
        return $this->fetchRow($select);
    }
    
}
?>
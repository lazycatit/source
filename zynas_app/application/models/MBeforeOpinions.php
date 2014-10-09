<?php
class MBeforeOpinions extends Db_Table {
    protected $_rowClass = 'MBeforeOpinion';
   

    function getEntries($product_number, $serialNumber){
        $select = $this->select()
        ->from(array('mbo' => $this->info('name')))
        ->where('mbo.product_number  = ?' ,$product_number)
        ->where('mbo.serial_number  = ?' ,$serialNumber)
        ->where('mbo.delete_flg <> ? or mbo.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        
        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);
        
        return $this->fetchAll($select);
    }
}
?>
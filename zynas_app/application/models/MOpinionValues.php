<?php
class MOpinionValues extends Db_Table {
    protected $_rowClass = 'MOpinionValue';
    
    function getEntry($item, $itemNumber, $type, $lawName, $productNumber, $productCategory){
        $select = $this->select()
        ->from(array('mov' => $this->info('name')))
        ->where('mov.item  = ?' ,$item)
        ->where('mov.product_category  = ?' , $productCategory)
        ->where('mov.product_number  = ?' , $productNumber)
        ->where('mov.item_number  = ?' ,$itemNumber)
        ->where('mov.type  = ?' ,$type)
        ->where('mov.law_name  = ?' ,$lawName)
        ->where('mov.delete_flg <> ? or mov.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        
        Log::debugLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);

        return $this->fetchRow($select);
    }
     
}
?>
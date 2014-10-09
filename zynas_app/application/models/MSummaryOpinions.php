<?php
class MSummaryOpinions extends Db_Table {

    protected $_rowClass = 'MSummaryOpinion';

    function getEntries($product_number, $product_category){
        $select = $this->select()
        ->from(array('mso' => $this->info('name')))
        ->where('mso.product_number  = ?' ,$product_number)
        ->where('mso.product_category  = ?' ,$product_category)
        ->where('mso.delete_flg <> ? or mso.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        
        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);
        
        return $this->fetchRow($select);
         
    }
    


}
?>
<?php
class MTemplatePages extends Db_Table {
    protected $_rowClass = 'MTemplatePage';


    function getEntry($item, $item_number, $type, $lawName){
        $select = $this->select()
        ->from(array('mtplp' => $this->info('name')))
        ->where('mtplp.item  = ?' ,$item)
        ->where('mtplp.item_number  = ?' ,$item_number)
        ->where('mtplp.type  = ?' ,$type)
        ->where('mtplp.law_name  = ?' ,$lawName)
        ->where('mtplp.delete_flg <> ? or mtplp.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        
        Log::debugLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);
        
        return $this->fetchRow($select);  

    }
}
?>
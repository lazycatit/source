<?php
class MTemplates extends Db_Table {
    protected $_rowClass = 'MTemplate';

    function getEntry($item, $item_number, $type, $lawName){
        $select = $this->select()
        ->from(array('mtpl' => $this->info('name')))
        ->where('mtpl.item  = ?' ,$item)
        ->where('mtpl.item_number  = ?' ,$item_number)
        ->where('mtpl.type  = ?' ,$type)
        ->where('mtpl.law_name  = ?' ,$lawName)
        ->where('mtpl.delete_flg <> ? or mtpl.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        
        Log::debugLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);
        
        return $this->fetchRow($select);

    }
}
?>
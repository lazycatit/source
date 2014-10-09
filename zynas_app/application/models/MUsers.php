<?php
class MUsers extends Db_Table {
    protected $_rowClass = 'MUser';
    const REMEMBER = 1;

    //user role
    const DISCO_USER = 'DU';
    const DISCO_ADMIN = 'DA';
    const END_USER = 'EU';


    /**
     * check existing of userID
     * @param email tring
     * @return boolean
     */
    public function getUserByUserId($user_id)
    {
        $select = $this->select()
        ->from(array('mur' => $this->info('name')))
        ->where('mur.user_id = ?' ,$user_id)
        ->where('mur.delete_flg <> ? OR mur.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        Log::debugLog('method='.__FUNCTION__.';user_id='.$user_id.';control_number=;'.';Select='.$select);
        return $this->fetchRow($select);

    }

    public function getUserById($user_id)
    {
        $select = $this->select()
        ->from(array('mur' => $this->info('name')))
        ->where('mur.id = ?' ,$user_id)
        ->where('mur.delete_flg <> ? OR mur.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        Log::debugLog('method='.__FUNCTION__.';user_id='.$user_id.';control_number=;'.';Select='.$select);
        return $this->fetchRow($select);
    }
    
    public function getUserByCustomerCode($customerCode)
    {
        $select = $this->select()
        ->from(array('mur' => $this->info('name')))
        ->where('mur.customer_code = ?' ,$customerCode)
        ->where('mur.delete_flg <> ? OR mur.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.';Select='.$select);
        return $this->fetchRow($select);
    }
    
}
?>
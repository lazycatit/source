<?php
class MControlNumbers extends Db_Table {
    protected $_rowClass = 'MControlNumber';

    public function getControlNumber($yymm=null) {

        $select = $this->select()
        ->from(array('mcn' => $this->info('name')))
        ->where('mcn.control_yymm = ?' ,$yymm)
        ->where('mcn.delete_flg <> ? OR mcn.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('mcn.control_number DESC');

        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);

        return $this->fetchRow($select);
    }


    public function lockTable() {
        $this->_db->getConnection()->exec("LOCK TABLES m_control_number AS mcn WRITE ,m_user AS mur WRITE, t_publish_info AS tpi WRITE");
    }

    public function unlockTable() {
        $this->_db->getConnection()->exec("UNLOCK TABLES");
    }
}
?>
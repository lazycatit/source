<?php
class MCustomers extends Db_Table
{
    protected $_rowClass = 'MCustomer';
    const RBT_ALL_CUST = 2;


    /**
     * Get list customer of Admin
     *
     * @param array $where
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getSelectDABySearchkey($where = array())
    {
        $select = $this->select()
        ->from(array('msc' => $this->info('name')))
        ->where('msc.delete_flg <> ? OR msc.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('customer_name ASC');
        if (isset($where['keysearch'])) {
            $select->where('msc.customer_code like (?', '%' . $where['keysearch'] . '%');
            $select->orwhere('msc.customer_name like ?)', '%' . $where['keysearch'] . '%');
        }
        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);

        return $select;

    }

    /**
     * Get list customer of Disco Staff
     *
     * @param array $where
     * @param string $userId
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getListSelectDU($where = array(), $userId = '')
    {
        $select = $this->select()
        ->from(array('msc' => $this->info('name')) )->setIntegrityCheck(false)
        ->join(array('mus' => MUsers::getInstance()->info('name')), 'msc.du_email = mus.user_id', array('msc.customer_name', 'msc.customer_code'))
        ->where('msc.delete_flg <> ? OR msc.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mus.delete_flg <> ? OR mus.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('(msc.du_email = ?', $userId)
        ->orwhere('msc.staff_email = ?)', $userId )
        ->order('customer_name ASC');

        if (isset($where['keysearch'])) {
            $select->where('msc.customer_code like (?', '%' . $where['keysearch'] . '%');
            $select->orwhere('msc.customer_name like ?)', '%' . $where['keysearch'] . '%');
        }

        if (isset($where['rbt_sel'])) {
            if ($where['rbt_sel'] == self::RBT_ALL_CUST) {
                $select = $this->select()
                ->from(array('msc' => $this->info('name')))
                ->where('msc.delete_flg <> ? OR msc.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
                ->order('customer_name ASC');
                if (isset($where['keysearch']) && (strcmp($where['keysearch'], '') !== 0)) {
                    $select->where('msc.customer_code like (?', '%' . $where['keysearch'] . '%');
                    $select->orwhere('msc.customer_name like ?)', '%' . $where['keysearch'] . '%');
                }
            }
        }

        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);

        return $select;

    }

    public function getEntryById($user_id)
    {
        $select = $this->select()
        ->from(array('msc' => MCustomers::getInstance()->info('name')))->setIntegrityCheck(false)
        ->joinLeft(array('mur' => MUsers::getInstance()->info('name')), 'msc.customer_code = mur.customer_code', array('mur.user_id', 'mur.email', 'mur.name_en', 'mur.user_type'))
        ->where('mur.user_id = ?' ,$user_id)
        ->where('msc.delete_flg <> ? OR msc.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mur.delete_flg <> ? OR mur.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);

        return $this->fetchRow($select);

    }

    public function getEntryByCode($customerCode) {
        $select = $this->select()
        ->from(array('msc' => MCustomers::getInstance()->info('name')))
        ->where('msc.customer_code = ?' ,$customerCode)
        ->where('msc.delete_flg <> ? OR msc.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
        Log::debugLog('method='.__FUNCTION__.';user_id='.';control_number=;'.$select);

        return $this->fetchRow($select);
    }
}
?>
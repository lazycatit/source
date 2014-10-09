<?php

class TOrders extends Db_Table {

    protected $_rowClass = 'TOrder';

    /**
     * 一覧表示用のデータを取得する
     * @param array $where
     * @param string $customer_code, string $category
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getListSelect($where = array(), $customer_code = null, $category = null) {
        $select = $this->select()
        ->from(array('tod' => $this->info('name')))->setIntegrityCheck(false)
        ->joinLeft(array('mcus' => MCustomers::getInstance()->info('name')), 'tod.client_code = mcus.customer_code', array('mcus.customer_code'))
        ->joinLeft(array('mpd' => MProducts::getInstance()->info('name')), 'tod.product_number = mpd.product_number', array('tod.order_number', 'tod.order_date', 'tod.product_name1', 'tod.product_name2', 'mpd.product_category', 'tod.product_number_view'))
        ->joinLeft(array('mca' => MCategorys::getInstance()->info('name')), 'mpd.product_category = mca.main_category', array('mca.name1'))
        ->where('tod.delete_flg <> ? OR tod.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mpd.delete_flg <> ? OR mpd.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mca.delete_flg <> ? OR mca.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mca.sub_category = ?', '00')
        ->where('mcus.customer_code = ?', $customer_code)
        ->order('tod.order_date DESC');

        if (isset($where['category']) && '' !== $where['category'] && strcmp($where['category'], '00') !== 0 && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to']))) && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            $select->where('mpd.product_category = ?', $where['category']);
        }
        if (isset($where['order_number']) && '' !== $where['order_number'] && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to']))) && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            //replace all comma to one whitespace
            $order_number = preg_replace('!\s+!', ',', $where['order_number']);
            $order_number = explode(Db_Table::COMMA, $order_number);
            $num = count($order_number);
            foreach ($order_number as $key => $value) {
                if (strcmp($num, '1') === 0) {
                    $select->where('tod.order_number like ?', '%' . $value . '%');
                } elseif (strcmp($key, '0') === 0) {
                    $select->where('(tod.order_number like ?', '%' . $value . '%');
                } elseif (strcmp($key, $num - 1) === 0) {
                    $select->orwhere('tod.order_number like ?)', '%' . $value . '%');
                } else {
                    $select->orwhere('tod.order_number like ?', '%' . $value . '%');
                }
            }
        }

        if (isset($where['create_from']) && '' !== $where['create_from'] && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to'])))) {

            $select->where('tod.order_date >= ?', strtotime($where['create_from'] . ' ' . Db_Table::FROM_HMS));
        }

        if (isset($where['create_to']) && '' !== $where['create_to'] && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            $select->where('tod.order_date <= ?', strtotime($where['create_to'] . ' ' . Db_Table::TO_HMS));
        }

        if (isset($where['product']) && '' !== $where['product'] && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to']))) && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            $select->where('tod.product_name1 like (?', '%' . $where['product'] . '%');
            $select->orwhere('tod.product_name2 like ?)', '%' . $where['product'] . '%');
        }
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . ';Select=' . $select);

        return $select;

    }
    
    /**
     * 
     * @param array $where
     * @param int $category
     * @return Zend_Db_Table_Abstract
     */    
    public function getListEntries($where = array(), $category = null) {
        $select = $this->select()
        ->from(array('tod' => $this->info('name')))->setIntegrityCheck(false)
        ->joinLeft(array('mcus' => MCustomers::getInstance()->info('name')), 'tod.client_code = mcus.customer_code', array('mcus.customer_code'))
        ->joinLeft(array('mpd' => MProducts::getInstance()->info('name')), 'tod.product_number = mpd.product_number', array('tod.order_number', 'tod.order_date', 'tod.product_name1', 'tod.product_name2', 'mpd.product_category', 'tod.product_number_view'))
        ->joinLeft(array('mca' => MCategorys::getInstance()->info('name')), 'mpd.product_category = mca.main_category', array('mca.name1'))
        ->where('tod.delete_flg <> ? OR tod.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mpd.delete_flg <> ? OR mpd.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mca.delete_flg <> ? OR mca.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mca.sub_category = ?', '00')
        ->order('tod.order_date DESC');

        if(isset($where['customer_code'])) {
            $select->where('mcus.customer_code = ?', $where['customer_code']);
        }

        if (isset($where['category']) && '' !== $where['category'] && strcmp($where['category'], '00') !== 0 && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to']))) && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            $select->where('mpd.product_category like ?', '%' . $where['category'] . '%');
        }
        if (isset($where['order_number']) && '' !== $where['order_number'] && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to']))) && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            //replace all comma to one whitespace
            $order_number = preg_replace('!\s+!', ',', $where['order_number']);
            $order_number = explode(Db_Table::COMMA, $order_number);
            $num = count($order_number);
            foreach ($order_number as $key => $value) {
                if (strcmp($num, '1') === 0) {
                    $select->where('tod.order_number like ?', '%' . $value . '%');
                } elseif (strcmp($key, '0') === 0) {
                    $select->where('(tod.order_number like ?', '%' . $value . '%');
                } elseif (strcmp($key, $num - 1) === 0) {
                    $select->orwhere('tod.order_number like ?)', '%' . $value . '%');
                } else {
                    $select->orwhere('tod.order_number like ?', '%' . $value . '%');
                }
            }
        }

        if (isset($where['create_from']) && '' !== $where['create_from'] && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to'])))) {

            $select->where('tod.order_date >= ?', strtotime($where['create_from'] . ' ' . Db_Table::FROM_HMS));
        }

        if (isset($where['create_to']) && '' !== $where['create_to'] && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            $select->where('tod.order_date <= ?', strtotime($where['create_to'] . ' ' . Db_Table::TO_HMS));
        }

        if (isset($where['product']) && '' !== $where['product'] && (('' !== $where['create_to_year'] && '' !== $where['create_to_month'] && '' !== $where['create_to_day']) || (isset($where['create_to']))) && (('' !== $where['create_from_year'] && '' !== $where['create_from_month'] && '' !== $where['create_from_day']) || (isset($where['create_from'])))) {

            $select->where('tod.product_name1 like (?', '%' . $where['product'] . '%');
            $select->orwhere('tod.product_name2 like ?)', '%' . $where['product'] . '%');
        }
        Log::debugLog('method=' . __FUNCTION__ . ';user_id=' . ';control_number=;' . ';Select=' . $select);

        return $select;

    }

}
?>
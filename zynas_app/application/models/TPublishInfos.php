<?php

class TPublishInfos extends Db_Table
{
    protected $_rowClass = 'TPublishInfo';
    const STATUS_NOT_ISSUE = 1;
    const STATUS_COMPLETE_ISSUE = 2;
    const MAX_DISPPLAY_ITEM = 6;
    const PUBLISH_TYPE_PAPER = 2;
    const PUBLISH_TYPE_PDF = 1;
    const CK_ON = 1;
    const CK_OFF = 0;
    const IS_CUSTOMER_FORMAT = 1;
    const IS_NOT_CUSTOMER_FORMAT = 0;
    const IS_CUSTOMER_DESIGNATION = '1';
    const IS_NOT_CUSTOMER_DESIGNATION = '0';

    /**
     * Get Unissue list
     * @param array $where
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getUnissueList($where = array()) {
        $select = $this->select()
        ->from(array('tpi' => $this->info('name')), array('*', 'number_of_col' => 'count(tpi.control_number)' )) -> setIntegrityCheck(false)
        ->joinLeft(array('tppi' => TPublishProductInfos::getInstance()->info('name')), 'tpi.control_number = tppi.control_number', array('tppi.product_name_view'))
        ->where('tpi.delete_flg <> ? OR tpi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tppi.delete_flg <> ? OR tppi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->group('tpi.id')
        ->order('tpi.control_number DESC');

        if (isset($where['rbt_mode']) && strcmp($where['rbt_mode'], self::STATUS_COMPLETE_ISSUE) === 0) {
            $select->where('tpi.status = ?', self::STATUS_COMPLETE_ISSUE );
        } else {
            $select->where('tpi.status = ?', self::STATUS_NOT_ISSUE);
        }

        if (isset($where['control_number']) && (strcmp($where['control_number'], '') !== 0)) {
            $select->where('tpi.control_number like ?', '%' . $where['control_number'] . '%');
        }
        
        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.';Select='.$select);
        return $select;
    }

    /**
     * Get Issued list EU
     * @param array $where
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getIssuedList($where = array()) {
        $select = $this->select()
        ->from(array('tpi' => $this->info('name')), array('*', 'number_of_col' => 'count(tpi.control_number)' )) -> setIntegrityCheck(false)
        ->joinInner(array('mcus' => MCustomers::getInstance()->info('name')), 'tpi.customer_code = mcus.customer_code', array('mcus.customer_type'))
        ->joinLeft(array('tppi' => TPublishProductInfos::getInstance()->info('name')), 'tpi.control_number = tppi.control_number', array('tppi.product_name_view'))
        ->where('tpi.delete_flg <> ? OR tpi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tppi.delete_flg <> ? OR tppi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mcus.delete_flg <> ? OR mcus.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tpi.create_user = ?', Auth_Info::getUser()->user_id)
        ->group('tpi.control_number')
        ->group('tpi.publish_type')
        ->order('tpi.control_number DESC');

        if(isset($where['customer_code']) && strcmp($where['customer_code'], '') !== 0){
            $select->where('tpi.customer_code = ?', $where['customer_code']);
        }

        if (isset($where['control_number']) && (strcmp($where['control_number'], '') !== 0)
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))
            &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))){

            $select->where('tpi.control_number like ?', '%' . $where['control_number'] . '%');
        }
        if (isset($where['create_from']) && '' !== $where['create_from']
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))) {

            $select->where('tpi.create_date >= ?', $where['create_from'] . ' ' . Db_Table::FROM_HMS);
        }
        if (isset($where['create_to']) && '' !== $where['create_to']
        &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))) {

            $select->where('tpi.create_date <= ?', $where['create_to'] . ' ' . Db_Table::TO_HMS);
        }

        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.';Select='.$select);
        return $select;
    }

    public function getDAIssuedList($where = array()) {
        $select = $this->select()
        ->from(array('tpi' => $this->info('name')), array('*', 'number_of_col' => 'count(tpi.control_number)' )) -> setIntegrityCheck(false)
        ->joinInner(array('mcus' => MCustomers::getInstance()->info('name')), 'tpi.customer_code = mcus.customer_code', array('mcus.customer_type'))
        ->joinLeft(array('tppi' => TPublishProductInfos::getInstance()->info('name')), 'tpi.control_number = tppi.control_number', array('tppi.product_name_view'))
        ->where('tpi.delete_flg <> ? OR tpi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tppi.delete_flg <> ? OR tppi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mcus.delete_flg <> ? OR mcus.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->group('tpi.control_number')
        ->group('tpi.publish_type')
        ->order('tpi.control_number DESC');

        if(isset($where['customer_code']) && strcmp($where['customer_code'], '') !== 0){
            $select->where('tpi.customer_code = ?', $where['customer_code']);
        }

        if (isset($where['control_number']) && (strcmp($where['control_number'], '') !== 0)
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))
            &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))){

            $select->where('tpi.control_number like ?', '%' . $where['control_number'] . '%');
        }
        if (isset($where['create_from']) && '' !== $where['create_from']
            &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))) {

            $select->where('tpi.create_date >= ?', $where['create_from'] . ' ' . Db_Table::FROM_HMS);
        }
        if (isset($where['create_to']) && '' !== $where['create_to']
        &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))) {

            $select->where('tpi.create_date <= ?', $where['create_to'] . ' ' . Db_Table::TO_HMS);
        }

        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.';Select='.$select);
        return $select;
    }

    /**
     * Get list of product's name
     * Issued history list and Unissue list use this function as common
     * @param string $control_number
     * @param int $limit
     * @param int $publish_type
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getListSelectProduct($control_number, $limit = null, $publish_type = null) {
        $select = $this->select()
        ->from(array('tpi' => $this->info('name')),array()) -> setIntegrityCheck(false)
        ->joinLeft(array('tppi' => TPublishProductInfos::getInstance()->info('name')), 'tpi.control_number = tppi.control_number', array('tppi.product_name_view'))
        ->where('tpi.delete_flg <> ? OR tpi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tppi.delete_flg <> ? OR tppi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tpi.control_number =?', $control_number)
        ->where('tpi.publish_type =?', $publish_type);
        if (isset($limit)) {
            $select->limit($limit, 0);
        }

        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number='.$control_number.';Select='.$select);
        return $this->fetchAll($select);
    }

    /**
     *  <pre>
     *  @get yymm of time request
     *  @get number Certificate in this yymm
     *  ->@return Manage No folder (Ayymm_number)
     *  </pre>
     **/
    function getManageNoFolder($where=array(), $create_from, $create_to){
        $select = $this->select()
        ->from(array('mtpl' => $this->info('name')), array('count(*) as number'))
        ->where('mtpl.delete_flg <> ? or mtpl.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('create_date <= ?', $create_to)
        ->where('create_date >= ?', $create_from);

        $results = $this->fetchAll($select);
        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.';Select='.$select);
        return ($results[0]->number);

    }


    /**
     * Set Publish infos data to save DB
     * <pre>
     * $get parameter row from request issue
     * </pre>
     */
    function setPublishInfosData($row){
        return $row;
    }

    /**
     * Get number of records in database by control_number
     * @param string $control_number
     * @return Zend_Db_Table_Row_Abstract
     */
    function countRecordByControlNumber($control_number) {
        $select = $this->select()
        ->from(array('tpi' => $this->info('name')), array('count(tpi.publish_type) as number'))
        ->where('tpi.delete_flg <> ? or tpi.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tpi.control_number = ?', $control_number);

        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.$control_number.';Select='.$select);
        return $this->fetchRow($select);
    }

    /**
     * Get data record by control_number and publish_type
     * @param string $control_number
     * @param int $publish_type
     * @return Zend_Db_Table_Row_Abstract
     */
    function getRow($control_number, $publish_type) {
    	$select = $this->select()
    	->from(array('tpi' => $this->info('name')))
    	->where('tpi.delete_flg <> ? or tpi.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
    	->where('tpi.control_number = ?', $control_number)
    	->where('tpi.publish_type = ?', $publish_type);

    	Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.$control_number.';Select='.$select);
    	return $this->fetchRow($select);
    }
    
    /**
     * Get create_date by control_number and publish_type = pdf
     * To get name of folder containing file download
     * @param unknown_type $control_number
     */
    function getCreateDateByControlNumber($control_number) {
        $select = $this->select()
        ->from(array('tpi' => $this->info('name')))
        ->where('tpi.delete_flg <> ? or tpi.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tpi.control_number = ?', $control_number)
        ->where('tpi.publish_type = ?', self::PUBLISH_TYPE_PDF);    
        
        Log::debugLog('method='.__FUNCTION__.';user_id;'.';control_number;'.$control_number.';Select='.$select);
        return $this->fetchRow($select);
    }
}

?>
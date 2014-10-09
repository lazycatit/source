<?php

class TPublishProductInfos extends Db_Table
{
    protected $_rowClass = 'TPublishProductInfo';    
    const PDF_CHECK = 1;
    const PAPER_CHECK = 1;
    const CONTROL_BRANCK_NUMBER = 1;
        
    /**
     * Get list of product's names by control_number
     * 
     * @param int $control_number
     * @return array
     */
    public function getProductsByControlNumber($control_number) {
        $select = $this->select()
        ->from(array('tppi' => $this->info('name')), array('*'))-> setIntegrityCheck(false)
        ->join(array('mc' => MCategorys::getInstance()->info('name')), 'tppi.product_category = mc.main_category', array('mc.name1'))
        ->where('tppi.delete_flg <> ? OR tppi.delete_flg  IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('tppi.control_number like ?', '%' . $control_number . '%')
        ->group('tppi.id')    
        ->order('tppi.control_number DESC');
        
        return $this->fetchAll($select);
    }
}

?>

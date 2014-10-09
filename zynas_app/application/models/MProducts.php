<?php
class MProducts extends Db_Table {
    protected $_rowClass = 'MProduct';
    const CATEGORY_DEVICE = '01';//装置
    const CATEGORY_SUBDEVICE = '02';//部品/ソフト
    const CATEGORY_PWP = '03';//ブレード/ホイール/Pad

    public function getListProductNumber($productCategory= null) {
        if(!isset($productCategory)) {
            $e = new Zynas_Exception();
            $e->setErrors(E068V);
            throw $e;
        }
        else {
            $select = $this->select()
            ->from(array('prd' => $this->info('name')))
            ->where('prd.delete_flg <> ? OR prd.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
            ->where('prd.product_category = ?', $productCategory)
            ->order('prd.product_number ASC');
            Log::debugLog('method='.__FUNCTION__.';user_id'.';control_number'.';Select='.$select);

            return $this->fetchAll($select);
        }
    }
    public function getProduct($productNumber = null) {
        $select = $this->select()
        ->from(array('prd' => $this->info('name')))
        ->where('prd.product_number = ?' ,$productNumber)
        ->where('prd.delete_flg <> ? OR prd.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('prd.product_number DESC');

        Log::debugLog('method='.__FUNCTION__.';user_id'.';control_number'.';Select='.$select);

        return $this->fetchRow($select);

    }

    public function getProductByProductNumberView($productNumberView = null) {
        $select = $this->select()
        ->from(array('prd' => $this->info('name')))
        ->where('prd.product_number_view = ?' ,$productNumberView)
        ->where('prd.delete_flg <> ? OR prd.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('prd.product_number DESC');

        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number'.';Select='.$select);

        return $this->fetchRow($select);

    }

    public function getProductByCategory($mainCategory, $subCategory) {
        $select  = $this->select()
        ->from(array('mp' => $this->info('name')))
        ->where('mp.delete_flg <> ? OR mp.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->where('mp.product_category =?', $mainCategory)
        ->where('mp.category =?', $subCategory)
        ->order('mp.product_number DESC');

        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number'.';Select='.$select);
        return $this->fetchRow($select);
    }
}
?>
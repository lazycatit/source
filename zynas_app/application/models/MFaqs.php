<?php
class MFaqs extends Db_Table {
    protected $_rowClass = 'MFaq';

    /**
     * 一覧表示用のデータを取得する
     * * @param array $where
     * @return Zend_Db_Table_Row_Abstract
     */
    public function getListSelect($where = array()) {
        $select = $this->select()
        ->from(array('mfaq' => $this->info('name')))
        ->where('mfaq.delete_flg <> ? OR mfaq.delete_flg IS NULL ', Db_Table::DELETE_FLG_ON)
        ->order('create_date DESC');

        if (isset($where['title']) && (strcmp($where['title'], '') !== 0)
        &&((isset($where['create_to_year']) && isset($where['create_to_month']) && isset($where['create_to_day']) && ''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))
        &&((isset($where['create_from_year']) && isset($where['create_from_month']) && isset($where['create_from_day']) && ''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))
        ) {
            //replace all whitespaces with one whitespace
            $title_key = preg_replace('!\s+!', ' ', $where['title']);
            $title_key = explode(Db_Table::SPACE, $title_key);
            $num = count($title_key);
            foreach ($title_key as $key => $value) {
                if (strcmp($num, '1') === 0) {
                    $select->where('mfaq.title like ?', '%' . $value . '%');
                } elseif (strcmp($key, '0') === 0) {
                    $select->where('(mfaq.title like ?', '%' . $value . '%');
                } elseif (strcmp($key, $num - 1) === 0){
                    $select->orwhere('mfaq.title like ?)', '%' . $value . '%');
                } else {
                    $select->orwhere('mfaq.title like ?', '%' . $value . '%');
                }
            }
        }
        if (isset($where['create_from']) && '' !== $where['create_from']
        &&((''!==$where['create_to_year'] && ''!==$where['create_to_month'] && ''!==$where['create_to_day'])||(isset($where['create_to'])))) {

            $select->where('create_date >= ?', $where['create_from'] . ' ' . Db_Table::FROM_HMS);
        }
        if (isset($where['create_to']) && '' !== $where['create_to']
        &&((''!==$where['create_from_year'] && ''!==$where['create_from_month'] && ''!==$where['create_from_day'])||(isset($where['create_from'])))) {

            $select->where('create_date <= ?', $where['create_to'] . ' ' . Db_Table::TO_HMS);
        }

        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);
        return $select;
    }

    public function getListSelectEU($where = array()) {
        $select = $this->select()
        ->from(array('mfaq' => $this->info('name')))
        ->where('mfaq.delete_flg <> ? OR mfaq.delete_flg IS NULL ', Db_Table::DELETE_FLG_ON)
        ->order('create_date DESC');

        if (isset($where['title']) && (strcmp($where['title'], '') !== 0)) {
            //replace all whitespaces with one whitespace
            $title_key = preg_replace('!\s+!', ' ', $where['title']);
            $title_key = explode(Db_Table::SPACE, $title_key);
            $num = count($title_key);
            foreach ($title_key as $key => $value) {
                if (strcmp($num, '1') === 0) {
                    $select->where('mfaq.title like ?', '%' . $value . '%');
                } elseif (strcmp($key, '0') === 0) {
                    $select->where('(mfaq.title like ?', '%' . $value . '%');
                } elseif (strcmp($key, $num - 1) === 0){
                    $select->orwhere('mfaq.title like ?)', '%' . $value . '%');
                } else {
                    $select->orwhere('mfaq.title like ?', '%' . $value . '%');
                }
            }
        }

        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number;'.';Select='.$select);
        return $select;
    }

    /**
     *
     * @param array $where
     * @param int $limit
     */
    public function getLimitList($where = array(), $limit = 10) {
        $select = $this->select()
        ->from(array('mfaq' => $this->info('name')))
        ->where('mfaq.delete_flg <> ? OR mfaq.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('create_date DESC');

        if (isset($where['title']) && (strcmp($where['title'], '') !== 0)) $select->where('mfaq.title like ?', '%' . $where['title'] . '%');
        if (isset($where['create_from']) && '' !== $where['create_from']){
            $select->where('create_date >= ?', $where['create_from']);
        }
        if (isset($where['create_to']) && '' !== $where['create_to']){
            $select->where('create_date <= ?', $where['create_to']);
        }
        $select->limit($limit, 0);

        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number'.';Select='.$select);
        return $this->fetchAll($select);
    }

    /**
     *
     * @return number
     */

    public function count() {
        $select = $this->select()
        ->from(array('mfaq' => $this->info('name')), array('num'=>'COUNT(id)'))
        ->where('mfaq.delete_flg <> ? OR mfaq.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
        ->order('create_date DESC');

        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number'.';Select='.$select);

        $result = $this->fetchRow($select);
        if($result)
        return $result->num;
        else
        return 0;
    }
}
?>
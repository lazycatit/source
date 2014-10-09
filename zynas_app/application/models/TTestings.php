<?php

/**
 *
 * @author TTM-DEV05
 *
 */
class TTestings extends Db_Table {
	protected $_rowClass = "TTesting";
	
	/**
	 * Lay ra toan bo bang
	 */
	public function getTestList() {
		$select = $this->select ()->from ( array (
				'ttesting' => $this->info ( 'name' ) 
		) )->where ( 'ttesting.delete_flg <> ? OR ttesting.delete_flg IS NULL', Db_Table::DELETE_FLG_ON );
		return $select;
	}
	public function getTestListByName($where) {
		$select = $this->select ()->from ( array (
				'ttesting' => $this->info ( 'name' ) 
		) )->where ( 'ttesting.delete_flg <> ? OR ttesting.delete_flg IS NULL', Db_Table::DELETE_FLG_ON );
		$select->where('ttesting.fullname like ?', '%' . $where . '%');
		return $select;
	}
}
?>
<?php
/**
 * 
 * @author TTM-DEV05
 *
 */
class TTestingdetails extends Db_Table{
	protected $_rowClass = "TTestingdetail";
	
	/**
	 * Lay ra danh sach chi tiet
	 */
	public function getListDetail(){
		$select = $this->select()
		->from(array('ttestdetail' => $this->info('name')));
		return $select;
	}
	
	/**
	 * Lay ra danh sach chi tiet
	 */
	public function getListDetail1(){
		$select = $this->select()
		->from(array('ttestdetail' => $this->info('name')));
		return $this->fetchAll($select);
	}
	
}
?>
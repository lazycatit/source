<?php
class MStaff extends Db_Table 
{
	protected  $_rowClass = "MStaff";
	
	public function getStaff($where = array() ){
		$select = $this->select()
		->from(array('stf' => $this->info('name')))
		->where('stf.delete_flg <> ? OR stf.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
	
		if (isset($where['name']) && (strcmp($where['name'], '') !== 0)) {
				
			$title_key = preg_replace('!\s+!', ' ', $where['name']);
			$title_key = explode(Db_Table::SPACE, $title_key);
			$num = count($title_key);
			foreach ($title_key as $key => $value) {
				if (strcmp($num, '1') === 0) {
					$select->where('stf.name like ?', '%' . $value . '%');
				} elseif (strcmp($key, '0') === 0) {
					$select->where('(stf.name like ?', '%' . $value . '%');
				} elseif (strcmp($key, $num - 1) === 0){
					$select->orwhere('stf.name like ?)', '%' . $value . '%');
				} else {
					$select->orwhere('stf.name like ?', '%' . $value . '%');
				}
			}
				
		}
	
		return $select;
	}
	
	/*public function getTestJoin($where = array()){
		$select = $this->select()
		->from(array('ttes' => $this->info('name')))
		->join(array('ttj' => TTestJoins::getInstance()->info('name')), 'ttes.id = ttj.test_id')
		->where('ttes.delete_flg <> ? OR ttes.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
		->where('ttj.delete_flg <> ? OR ttj.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
	
		return $select;
	}*/
}
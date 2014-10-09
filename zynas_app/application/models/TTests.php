<?php
class TTests extends Db_Table{
	protected $_rowClass = "TTest";
	
	public function getTest($where = array() ){
		$select = $this->select()
		->from(array('ttes' => $this->info('name')))
		->where('ttes.delete_flg <> ? OR ttes.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
		
		if (isset($where['name']) && (strcmp($where['name'], '') !== 0)) {
			
			$title_key = preg_replace('!\s+!', ' ', $where['name']);
			$title_key = explode(Db_Table::SPACE, $title_key);
			$num = count($title_key);
			foreach ($title_key as $key => $value) {
				if (strcmp($num, '1') === 0) {
					$select->where('ttes.name like ?', '%' . $value . '%');
				} elseif (strcmp($key, '0') === 0) {
					$select->where('(ttes.name like ?', '%' . $value . '%');
				} elseif (strcmp($key, $num - 1) === 0){
					$select->orwhere('ttes.name like ?)', '%' . $value . '%');
				} else {
					$select->orwhere('ttes.name like ?', '%' . $value . '%');
				}
			}
			
		}

		return $select;
	}
	
	public function getTestJoin($where = array()){
		$select = $this->select()
		->from(array('ttes' => $this->info('name')))
		->join(array('ttj' => TTestJoins::getInstance()->info('name')), 'ttes.id = ttj.test_id')
		->where('ttes.delete_flg <> ? OR ttes.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
		->where('ttj.delete_flg <> ? OR ttj.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);

		return $select;
	}
}
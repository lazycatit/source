<?php
class StaffController extends Controller {
	const SESSION_KEY_PAGE = 'page';
	const SESSION_KEY_SEARCH = 'search';
	const SESSION_KEY_RETURN_DETAIL = 'return';
	
	public function indexAction() {
		Log::infoLog('method='.__FUNCTION__.';staff_id='.Auth_Info::getUser()->staff_id.';control_number'.';Start action');
		Log::infoLog('method='.__FUNCTION__.';staff_id='.Auth_Info::getUser()->staff_id.';control_number'.';End action');
		$this->_forward('/list');
	}
	
	public function listAction()
	{
		$this->view->role = Auth_Info::getUser()->user_type;
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		
		if($this->getRequest()->isGet()&&!isset($this->_input->page)){
			$this->session->removeData(self::SESSION_KEY_SEARCH);
		}
		
		$this->session->removeData(self::SESSION_KEY_PAGE);
		$this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
		
		$page = null;
		if ($this->getRequest()->isPost()) {
			$where = $this->_input->getEscaped();
			if(isset($this->getRequest()->name) && !isset($where['name'])){
				$where = array();
			}
		} else {
			$where = $this->session->getData(self::SESSION_KEY_SEARCH);
			if (is_null($where)) {
				$where = array();
			}
			$page = isset($this->_input->page) ? $this->_input->page : $this->session->getData(self::SESSION_KEY_PAGE);
		}
		
		$this->getRequest()->setParams($where);
		$this->session->setModuleScope(self::SESSION_KEY_SEARCH, $where);
		$this->session->setModuleScope(self::SESSION_KEY_PAGE, $page);
		
		$select = TTests::getInstance()->getTest($where);
		$this->view->max_display_char = Zynas_Registry::getConfig()->constants->max_display_char;
		$this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	}
	
	public function detailAction() 
	{
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		$this->view->token = Csrf::getToken();
		
		$id = $this->_input->id;
		$row = TTests::getInstance()->getEntryById($id);
		$this->view->infDetail = $row;
		
		$this->view->infDetail = $row;
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	}
	
	public function addStaffAction(){
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		$this->view->token = Csrf::getToken();
	
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	}
	
	public function confirmAddAction() {
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		if(!Csrf::checkToken($this->_input->token)) {
			FlashMessenger::addError(E061V);
			$this->_redirect('/staff/list');
		}
	
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		try {
			$row = TTests::getInstance()->createRow();
			$row->create_date = Zynas_Date::dbDatetime();
			$row ->create_staff =  Auth_Info::getUser()->user_id;
			$row = $this->setInfoData($row);
			$row->save();
	
			FlashMessenger::addSuccess(E065V);
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			FlashMessenger::addError($e->getMessage());
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	
		$this->_redirect('/staff/list');
	}
	
	private function setInfoData($row){
			
		$row->name = $this->_input->name;
		$row->position = $this->_input->sbt_pos;
		$row->email = $this->_input->email;
		return $row;
	}
	
	public function editAction(){
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
	
		$this->view->token = Csrf::getToken();
		$id = $this->_input->id;
		$row = TTests::getInstance()->getEntryById($id);
		$this->getRequest()->setParams($row->toArray());
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	
	}
	
	
	public function confirmEditAction() {
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		if(!Csrf::checkToken($this->_input->token)) {
			FlashMessenger::addError(E061V);
			$this->_redirect('/test/list');
		}
		//螟画峩逕ｻ髱｢縺九ｉ蜈･蜉帛�螳ｹ繧貞叙蠕励☆繧�
		$id = $this->_input->id;
	
		//蟇ｾ雎｡繝ｬ繧ｳ繝ｼ繝峨ｒ蜿門ｾ�
		$informationRow = TTests::getInstance()->getEntryById($id);
	
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		try {
			$informationRow = $this->setInfoData($informationRow);
			$informationRow->update_date = Zynas_Date::dbDatetime();
			$informationRow->update_user = Auth_Info::getUser()->user_id;
			$informationRow->save();
			$db->commit();
			FlashMessenger::addSuccess(E074V);
		} catch (Exception $e) {
			$db->rollBack();
			FlashMessenger::addError($e->getMessage());
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		return $this->_redirect('/test/list');
	}
	
	public function deleteAction() {
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
	
		if (!$this->getRequest()->isPost()){
			FlashMessenger::addError(E062V);
			$this->_redirect('/test/list');
		}
	
		$id = $this->_input->id;
		$row = TTests::getInstance()->getEntryById($id);
	
		if (is_Null($row)) {
			// error
			$this -> handleErrorDelete();
		}
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		try {
			$row->delete_flg = MInformations::DELETE_FLG_ON;
			$row->delete_date = Zynas_Date::dbDatetime();
			$row->delete_flg_update_user = Auth_Info::getUser()->user_id;
			$row->save();
			$db->commit();
			FlashMessenger::addSuccess(I002V);
	
		} catch (Exception $e) {
			$db->rollBack();
			FlashMessenger::addError($e->getMessage());
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		$this->_redirect('/test/list');
	}
	
	public function joinAction(){
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
	
		$select = TTests::getInstance()->getTestJoin($where);
	
		$this->view->data = $select;
	
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	}
}

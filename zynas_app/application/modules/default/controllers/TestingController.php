<?php

/**
 * Testing controller
 * Su dung t_testing va t_testingdetail table trong zynasgaihi database
 * de test
 * @author TTM-DEV05
 * @package /modules/default/controller
 */
class TestingController extends Controller {
	private $username;
	private $password;
	private $fullname;
	private $addressid;
	
	/**
	 * Action mac dinh
	 * Se tu chuyen den chuc nang /index --> /add
	 */
	public function indexAction() {
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';Start action' );
		
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';End action' );
		$this->_forward ( '/list' );
	}
	
	/**
	 * Them 1 nguoi dung moi
	 */
	public function addAction() {
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';Start action' );
		// Day token len view
		$this->view->token = Csrf::getToken ();
		
		// Lay ra danh sach chi tiet
		$row1 = TTestingdetails::getInstance ()->getListDetail1 ();
		$lst = array ();
		foreach ( $row1 as $k => $val ) {
			$lst [$val ["id"]] = $val ["position"];
		}
		$this->view->lstoption = $lst;
		
		// POST
		if ($this->getRequest ()->isPost ()) {
			// Neu khong co token thi khong cho chuyen huong
			if (! Csrf::checkToken ( $this->_input->token )) {
				FlashMessenger::addError ( E061V );
				$this->_redirect ( '/testing/' );
			}
			
			// Lay ra du lieu tu form
			$this->GetData ();
			
			// Thuc hien them du lieu vao
			$db = Zynas_Db_Table::getDefaultAdapter ();
			$db->beginTransaction ();
			try {
				$row = TTestings::getInstance ()->createRow ();
				$row->username = $this->username;
				$row->password = $this->password;
				$row->fullname = $this->fullname;
				$row->t_addressid = $this->addressid;
				$row->save ();
				// Message hien thi them vao thanh cong
				// FlashMessenger::addSuccess(E061V);
				FlashMessenger::addSuccess ( 'Them vao OK' );
				$db->commit ();
			} catch ( Exception $e ) {
				$db->rollBack ();
				// Message hien thi them vao that bai
				FlashMessenger::addError ( $e->getMessage () );
			}
			Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';End action' );
		}
	}
	
	/**
	 * Update 1 nguoi dung moi
	 */
	public function editAction() {
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';Start action' );
		// Day token len view
		$this->view->token = Csrf::getToken ();
		// Lay ra id nguoi dung
		$id = $this->_input->id;
		$row = TTestings::getInstance ()->getEntryById ( $id );
		$this->getRequest ()->setParams ( $row->toArray () );
		
		// Lay ra danh sach chi tiet
		$row1 = TTestingdetails::getInstance ()->getListDetail1 ();
		$lst = array ();
		foreach ( $row1 as $k => $val ) {
			$lst [$val ["id"]] = $val ["position"];
		}
		$this->view->lstoption = $lst;
		
		if ($this->getRequest ()->isPost ()) {
			// Neu khong co token thi khong cho chuyen huong
			if (! Csrf::checkToken ( $this->_input->token )) {
				FlashMessenger::addError ( E061V );
				$this->_redirect ( '/testing/' );
			}
			// Lay ra du lieu tu form
			$this->GetData ();
			$id = $this->_input->id;
			
			// Thuc hien chinh sua du lieu
			$db = Zynas_Db_Table::getDefaultAdapter ();
			$db->beginTransaction ();
			try {
				// Lay du lieu ra de chinh sua
				$row = TTestings::getInstance ()->getEntryById ( $id );
				$row->username = $this->username;
				$row->password = $this->password;
				$row->fullname = $this->fullname;
				$row->t_addressid = $this->addressid;
				$row->save ();
				// Message hien thi them vao thanh cong
				// FlashMessenger::addSuccess(E061V);
				FlashMessenger::addSuccess ( 'Chinh sua OK' );
				$db->commit ();
				
				// Refresh lai trang
				$this->view->token = Csrf::getToken ();
				$redirect = strval ( '/testing/edit?id=' . $id );
				$this->_redirect ( $redirect );
			} catch ( Exception $e ) {
				$db->rollBack ();
				// Message hien thi them vao that bai
				FlashMessenger::addError ( $e->getMessage () );
			}
		}
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';End action' );
	}
	
	/**
	 * Xoa 1 nguoi dung moi
	 */
	public function deleteAction() {
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';Start action' );
		// Chi nhan POST, neu la GET thi tra ve
		if ($this->getRequest ()->isPost ()) {
			$id = $this->_input->deleteid;
			
			// Lay du lieu ra de chinh sua
			$row = TTestings::getInstance ()->getEntryById ( $id );
			// Neu row khong ton tai thi thong bao loi
			if (is_Null ( $row )) {
				FlashMessenger::addError ( "Khong ton tai row" );
				$this->_redirect ( '/testing/list' );
			}
			
			$db = Zynas_Db_Table::getDefaultAdapter ();
			$db->beginTransaction ();
			try {
				$row->delete_flg = MInformations::DELETE_FLG_ON;
				$row->delete_date = Zynas_Date::dbDatetime ();
				$row->delete_flg_update_user = Auth_Info::getUser ()->user_id;
				$row->save ();
				// Message hien thi them vao thanh cong
				// FlashMessenger::addSuccess(E061V);
				FlashMessenger::addSuccess ( 'Delete OK' );
				$db->commit ();
				$this->_redirect ( '/testing/list' );
			} catch ( Exception $e ) {
				$db->rollBack ();
				// Message hien thi them vao that bai
				FlashMessenger::addError ( $e->getMessage () );
				$this->_redirect ( '/testing/list' );
			}
		} else {
			FlashMessenger::addError ( E062V );
			$this->_redirect ( '/testing/list' );
		}
		
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';End action' );
	}
	
	/**
	 * Hien thi danh sach 1 nguoi dung moi
	 */
	public function listAction() {
		$this->view->role = Auth_Info::getUser ()->user_type;
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';Start action' );
		
		$page = null;
		// Doan nay phan biet POST voi GET
		if ($this->getRequest ()->isPost ()) {
			// Trong nay danh cho thu tuc post
		} else {
			// Trong nay danh cho thu tuc get
			
			// Lay ra danh sach account
			$select = TTestings::getInstance ()->getTestList ();
			$this->view->paginator = Zynas_Paginator::factoryWithOptions ( $select, $page, $this->view );
			// Lay ra danh sach chi tiet
			$select1 = TTestingdetails::getInstance ()->getListDetail ();
			$this->view->paginator1 = Zynas_Paginator::factoryWithOptions ( $select1, $page, $this->view );
		}
		Log::infoLog ( 'method=' . __FUNCTION__ . ';user_id=' . Auth_Info::getUser ()->user_id . ';control_number' . ';End action' );
	}
	
	/**
	 * Search function uses AJAX
	 */
	public function searchAction() {
		$page = null;
		if ($this->getRequest ()->isPost ()) {
			$search = $this->_input->search;
			// Lay ra danh sach account
			$select = TTestings::getInstance ()->getTestListByName ( $search );
			$this->view->paginator = Zynas_Paginator::factoryWithOptions ( $select, $page, $this->view );
			
			echo $this->view->render ( '_partial/listtesting.phtml' );
			exit ();
		}
	}
	private function GetData() {
		// Lay du lieu tu form
		$this->username = $this->_input->username;
		$this->password = $this->_input->password;
		$this->fullname = $this->_input->fullname;
		$this->addressid = $this->_input->position;
	}
}

?>
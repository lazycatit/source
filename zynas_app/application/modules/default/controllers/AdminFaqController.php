<?php
/**
 * <pre>
 * </pre>
 * @author AnhNV
 * @package /modules/default/controller
 */

class AdminFaqController extends Controller {

    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';

    /**
     * 初期表示のアクション
     * <pre>
     * 1)一覧表示アクションにフォワードする
     * </pre>
     */
    public function indexAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number'.';Start action');
        $this->_forward('/list');
        Log::infoLog('method='.__FUNCTION__.';user_id'.';control_number'.';Start action');
    }

    /**
     * Process list faqs
     * <pre>
     * 1) get data list
     *   	- If search value not null
     *   			-> return list data search
     *   	- If padding order
     *   			-> return data list with page number order
     * 2)1) Get data from (1) to View
     * </pre>
     */
    public function listAction() {
        $role = Auth_Info::getUser()->user_type;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');

        if($this->getRequest()->isGet()&&!isset($this->_input->page)){
            $this->session->removeData(self::SESSION_KEY_SEARCH);
        }
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);

        if(strcmp($role, 'DA'))
        $this->_redirect('/error');

        $page = null;
        if ($this->getRequest()->isPost()) {
            $where = $this->_input->getEscaped();
            if(isset($this->getRequest()->title) && !isset($where['title'])){
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

        $select = MFaqs::getInstance()->getListSelect($where);
        $this->view->max_display_char = Zynas_Registry::getConfig()->constants->max_display_char;
        $this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * Add faq data
     * <pre>
     * 	- Get data form add view input
     * 			-> get data and put to confirm add action
     *
     * </pre>
     */
    public function addAction() {
        $role = Auth_Info::getUser()->user_type;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if(strcmp($role, 'DA'))
        $this->_redirect('/error');
        $this->view->token = Csrf::getToken();
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * Action process register faqs
     * <pre>
     * 1) Get data from add View
     * 2) Set Dataset faq
     * 3) Query data to insert database
     * </pre>
     */
    public function confirmAddAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if(!Csrf::checkToken($this->_input->token)) {
            FlashMessenger::addError(E061V);
            $this->_redirect('/admin-faq/list');
        }

        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $row = MFaqs::getInstance()->createRow();
            $row->create_date = Zynas_Date::dbDatetime();
            $row ->create_user = Auth_Info::getUser()->user_id;
            $row = $this->setInfoData($row);
            $row->save();
            FlashMessenger::addSuccess(E065V);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            FlashMessenger::addError($e->getMessage());
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        $this->_redirect('/admin-faq/list');
    }
     
    /**
     *
     */
    public function doAddAction() {
         

    }

    private function setInfoData($row) {
        $row->title = $this->_input->title;
        $row->question = $this->_input->question;
        $row->answer = $this->_input->answer;
        return $row;
    }

    /**
     * Initial of edit action
     * <pre>
     * 1)Do nothing if data is not get from form via GET method
     * 2)Based on the input values to retrieve record in database
     * 3)2)process edit data from (2)
     * </pre>
     */
    public function editAction() {
        $role = Auth_Info::getUser()->user_type;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if(strcmp($role, 'DA'))
        $this->_redirect('/error');
        $this->view->token = Csrf::getToken();
        $id = $this->_input->id;
        $row = MFaqs::getInstance()->getEntryById($id);
        $this->getRequest()->setParams($row->toArray());
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * Confirmation about edit action
     * <pre>
     * 1)Display data in Edit form
     * </pre>
     */
    public function confirmEditAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if(!Csrf::checkToken($this->_input->token)) {
            FlashMessenger::addError(E061V);
            $this->_redirect('/admin-faq/list');
        }
        //変更画面から入力内容を取得する
        $id = $this->_input->id;

        //対象レコードを取得
        $faqRow = MFaqs::getInstance()->getEntryById($id);

        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $faqRow = $this->setInfoData($faqRow);
            $faqRow ->update_date = Zynas_Date::dbDatetime();
            $faqRow ->update_user = Auth_Info::getUser()->user_id;
            $faqRow->save();
            $db->commit();
            FlashMessenger::addSuccess(E074V);
        } catch (Exception $e) {
            $db->rollBack();
            FlashMessenger::addError($e->getMessage());
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        return $this->_redirect('/admin-faq/list');
    }

    /**
     * Change user action
     * <pre>
     * 1)Based on the input values to update record in database
     * </pre>
     */
    public function doEditAction(){

    }


    /*
     * Do detail faq
     * <pre>
     * 1) Get faq id
     * 2) Get data faq by Id
     * 3) Display data to view detail
     * </pre>
     **/
    public function detailAction(){
        $role = Auth_Info::getUser()->user_type;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if(strcmp($role, 'DA'))
        $this->_redirect('/error');
        $this->view->token = Csrf::getToken();
        $id = $this->_input->id;
        $row = MFaqs::getInstance()->getEntryById($id);
        $this->view->faqDetail = $row;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }



    /**
     * Delete action
     * <pre>
     * 1)Do nothing if data is not get from form via POST method
     * 1-1)Get Id from request parameter
     * </pre>
     */
    public function deleteAction() {
        $role = Auth_Info::getUser()->user_type;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if(strcmp($role, 'DA'))
        $this->_redirect('/error');

        if (!$this->getRequest()->isPost()){
            FlashMessenger::addError(E062V);
            $this->_redirect('/admin-faq/list');
        }

        $id = $this->_input->id;
        $row = MFaqs::getInstance()->getEntryById($id);

        if (is_Null($row)) {
            // error
            $this -> handleErrorDelete();
        }

        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $row->delete_flg = MFaqs::DELETE_FLG_ON;
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
        $this->_redirect('/admin-faq/list');
    }

    /**
     *	Preview function
     *	Get data from add form -> Prevew
     *  Get data from edit form -> Preview
     **/
    public function previewAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if ($this->getRequest()->isPost()){
            $array = array('title'=>$this->_input->title, 'question'=>$this->_input->question, 'answer'=>$this->_input->answer, 'create_date'=>Zynas_Date::dbDatetime());
            $this->getRequest()->setParams($array);
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     *ID取得失敗時の表示処理
     * <pre>
     * </pre>
     */
    public function handleErrorDelete() {
        FlashMessenger::addError(E075V);
        $this->_redirect('/admin-faq/list');
    }

    public function handleErrorList() {
    }
}
?>
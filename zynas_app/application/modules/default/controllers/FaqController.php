<?php
/**
 * <pre>
 * </pre>
 * @author AnhNV
 * @package /modules/default/controller
 */

class FaqController extends Controller {

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
        $this->_forward('/list');
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
    public function listAction(){
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

        $select = MFaqs::getInstance()->getListSelectEU($where);
        $this->view->max_display_char = Zynas_Registry::getConfig()->constants->max_display_char;
        $this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
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
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $this->view->token = Csrf::getToken();
        $id = $this->_input->id;
        $row = MFaqs::getInstance()->getEntryById($id);        
        $this->view->faqDetail = $row;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');

    }

    public function handleErrorDetail() {
        FlashMessenger::addError(E076V);
        $this->_redirect('/faq/list');
    }
    
    public function handleErrorList() {
    }
}
?>
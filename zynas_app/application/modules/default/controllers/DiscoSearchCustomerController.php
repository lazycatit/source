<?php

class DiscoSearchCustomerController extends Controller
{
    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';
    const SESSION_KEY_USER_ID = 'user_id';
    const FROM_ADMIN_ISSUE_HIST = 1;
    const FROM_DISCO_ISSUE_HIST = 2;
    const FROM_ADMIN_REQUEST_ISSUE = 3;
    const FROM_DISCO_REQUEST_ISSUE = 4;

    public function indexAction()
    {        
        $this->_forward('/list');
    }

    public function listAction()
    {
        $userId = Auth_Info::getUser()->user_id;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if($this->getRequest()->isGet()&&!isset($this->_input->page)){
            $this->session->removeData(self::SESSION_KEY_SEARCH);
        }
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
        $page = null;
        $targetUrl;
        if ($this->getRequest()->isPost()) {
            $where = $this->_input->getEscaped();
            if(isset($this->getRequest()->keysearch) && !isset($where['keysearch'])){
                $where = array();
            }
        } else {
            if (isset($this -> _input -> target)) {
                $target = $this -> _input -> target;
                if (strcmp($target, self::FROM_ADMIN_ISSUE_HIST) === 0) {
                    $targetUrl = "/da-issued-hist/add-customer";
                } else if (strcmp($target, self::FROM_ADMIN_REQUEST_ISSUE) === 0) {
                    $targetUrl = "/da-issue/add-customer";
                } else if (strcmp($target, self::FROM_DISCO_ISSUE_HIST) === 0) {
                    $targetUrl = "/du-issued-hist/add-customer";
                } else if (strcmp($target, self::FROM_DISCO_REQUEST_ISSUE) === 0) {
                    $targetUrl = "/du-issue/add-customer";
                }
            }
            $where = $this->session->getData(self::SESSION_KEY_SEARCH);
            if (is_null($where)) {
                $where = array();
            }
            $page = isset($this->_input->page) ? $this->_input->page : $this->session->getData(self::SESSION_KEY_PAGE);
            if(!isset($where['target'])) 
                $where['target'] = $targetUrl;
        }
        $this->getRequest()->setParams($where);
        $this->session->setModuleScope(self::SESSION_KEY_SEARCH, $where);
        $this->session->setModuleScope(self::SESSION_KEY_USER_ID, $where);
        $this->session->setModuleScope(self::SESSION_KEY_PAGE, $page);
        $select = MCustomers::getInstance()->getListSelectDU($where, $userId);
        $this->view->max_display_char = Zynas_Registry::getConfig()->constants->max_display_char;
        $this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
        $this->view->error = E045V;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    public function handleErrorList() {

    }
}

?>
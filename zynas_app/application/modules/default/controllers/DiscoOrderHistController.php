<?php

class DiscoOrderHistController extends Controller
{

    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';
    const SESSION_KEY_USER_TYPE = 'user_type';
    const SESSION_KEY_USER_ID = 'user_id';
    const CATEGORY_DEFAULT_VALUE = '00';

    /**
     * 初期表示のアクション
     * <pre>
     * 1)一覧表示アクションにフォワードする
     * </pre>
     */
    public function indexAction()
    {        
        $this->_forward('/list');
    }

    /**
     * Process list Order
     * <pre>
     * 1) get data list
     *   	- If search value not null
     *   			-> return list data search
     *   	- If padding order
     *   			-> return data list with page number order
     * 2)1) Get data from (1) to View
     * </pre>
     */
    public function listAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if($this->getRequest()->isGet()&&!isset($this->_input->page)){
            $this->session->removeData(self::SESSION_KEY_SEARCH);
        }
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
        if (isset($this->_input->code)) {
            $customer_code = $this->_input->code;
        } else {
            $customer_code = '';
        }
        if (isset($this->_input->category) && $this->_input->category !== '0') {
            $category = $this->_input->category;
        } else {
            $category = '';
        }
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

        $cbb = $this->_input->category;
        $this->session->setModuleScope(self::SESSION_KEY_SEARCH, $where);
        $this->session->setModuleScope(self::SESSION_KEY_USER_ID, $where);
        $this->session->setModuleScope(self::SESSION_KEY_PAGE, $page);

        $select = TOrders::getInstance()->getListSelect($where, $customer_code, $category);
        $this->view->code = $customer_code;
        $this ->view -> category = $this->_input->category;
        $this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }   
    
    

    /**
     * Create select box to select Category
     * <pre>
     * </pre>
     * @return string
     */
    function setSelectbox()
    {
        $categories = MCategorys::getInstance()->getSelectBoxCategoryAdmin(self::CATEGORY_DEFAULT_VALUE);
        
        return $categories;
    }
    
    /**
     *
     * <pre>
     * </pre>
     */
    public function handleErrorList() {
        FlashMessenger::addError(E078V);
        $this->_redirect('/admin-issue/request');
    }
}

?>
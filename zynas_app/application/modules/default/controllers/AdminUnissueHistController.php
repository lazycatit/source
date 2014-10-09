<?php

class AdminUnissueHistController extends Controller
{

    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';
     
    /**
     * 初期表示のアクション
     * <pre>
     * 1)一覧表示アクションにフォワードする
     * </pre>
     */
    public function indexAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        $this->_forward('/list');
    }

    /**
     * Process list Issue
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
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.'Start action');
        if ($this->getRequest()->isGet() && !isset($this->_input->page)) {
            $this->session->removeData(self::SESSION_KEY_SEARCH);
        }
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);

        $page = null;
        if ($this->getRequest()->isPost()) {
            $where = $this->_input->getEscaped();
            if(isset($this->getRequest()->control_number) && !isset($where['control_number'])){
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
        $t_publish_info = TPublishInfos::getInstance();
        $select = $t_publish_info->getUnissueList($where);
        $this->view->paper = TPublishInfos::PUBLISH_TYPE_PAPER;
        $this->view->pdf = TPublishInfos::PUBLISH_TYPE_PDF;
        $this->view->max_display = TPublishInfos::MAX_DISPPLAY_ITEM;
        $array = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
        foreach($array as $item) {
            $arrayProduct = $t_publish_info->getListSelectProduct($item->control_number, '', $item->publish_type);
            $item->setProducts($arrayProduct);
        }
        $this->view->paginator = $array;
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.'Start action');
    }
    /**
     *
     * <pre>
     * </pre>
     */
    public function handleErrorList() {

    }

}

?>
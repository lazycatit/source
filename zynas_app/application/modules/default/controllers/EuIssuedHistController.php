<?php
class EuIssuedHistController extends Controller {
    const URL_ROOT_UPLOAD = '/var/parameterSheet/';
    const SEPARATOR = '/';
    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';
    const SESSION_KEY_USER_ID = 'user_id';

    public function indexAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        $this->_forward('/list');
    }

    public function downloadAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $id = '';
        if (isset($this->_input->id)) {
            $id = $this->_input->id;
        } else {
            throw new Zynas_Exception('Can not get id');
        }

        $file_name = '';
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if (isset($this->_input->filename)) {
            $file_name = $this->_input->filename;
        } else {
            throw new Zynas_Exception('Can not get file name');
        }

        //Get extension of file download
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        
        $detail = TPublishInfos::getInstance()->getCreateDateByControlNumber($id);
        $yyyymm = str_replace('-' , '', substr($detail->create_date, 0, 7));

        //Set header for each type of files
        header("Content-Type: application/pdf");
        $tfile = APPLICATION_PATH . self::URL_ROOT_UPLOAD . $yyyymm . self::SEPARATOR . $id . self::SEPARATOR . $file_name;
        header("Content-Length: " . filesize($tfile));
        header('Content-Transfer-Encoding: Binary');
        header('Accept-Ranges: bytes');

        header('ETag: "' . $tfile . '"');
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");

        FileManager::readfileChunked($tfile);
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    public function listAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user-> user_id . ';control_number' . ';Start action');
        if(strcmp($user->user_type, MUsers::END_USER) != 0){
            $this->_redirect('/error');
            return;
        }
        if ($this->getRequest()->isGet() && !isset($this->_input->page)) {
            $this->session->removeData(self::SESSION_KEY_SEARCH);
        }
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
        $page = null;
        if ($this->getRequest()->isPost()) {
            $customerCode = $this->_input ->txtCustomerCode;
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
        $where['customer_code'] = $user->customer_code;
        $this->getRequest()->setParams($where);
        $this->session->setModuleScope(self::SESSION_KEY_SEARCH, $where);
        $this->session->setModuleScope(self::SESSION_KEY_USER_ID, $where);
        $this->session->setModuleScope(self::SESSION_KEY_PAGE, $page);
        $t_publish_info = TPublishInfos::getInstance();
        $select = $t_publish_info->getIssuedList($where);
        $this->view->customer = MCustomers::getInstance()->getEntryByCode($user->customer_code);
        $this->view->not_issue = TPublishInfos::STATUS_NOT_ISSUE;
        $this->view->complete_issue = TPublishInfos::STATUS_COMPLETE_ISSUE;
        $this->view->paper = TPublishInfos::PUBLISH_TYPE_PAPER;
        $this->view->pdf = TPublishInfos::PUBLISH_TYPE_PDF;
        $this->view->max_display = TPublishInfos::MAX_DISPPLAY_ITEM;
        $array = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
        foreach ($array as $item) {
            $arrayProduct = $t_publish_info->getListSelectProduct($item->control_number, '', $item->publish_type);
            $item->setProducts($arrayProduct);
        }
        $this->view->paginator = $array;
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user-> user_id . ';control_number' . ';End action');
    }

    public function handleErrorList() {
    }

}

<?php
class DaIssuedHistController extends Controller {

    const URL_ROOT_UPLOAD = '/var/parameterSheet/';
    const SEPARATOR = '/';
    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';
    const SESSION_KEY_USER_ID = 'user_id';
    const COOKIE_CUSTOMER_CODE = "CUSTOMER_CODE";
    const SESSION_CUSTOMER_NAME = 'CUSTOMER_NAME';
    const SESSION_KEY_DETAIL = 'detail';

    public function indexAction() {
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
        
        $detail = $this->session->getData(self::SESSION_KEY_DETAIL);
        $yyyymm = str_replace('-' , '', substr($detail->create_date, 0, 7));
        
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
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number' . ';Start action');
        if (strcmp($user->user_type, MUsers::DISCO_ADMIN) != 0) {
            throw new Zynas_Exception('This user is not DA');
            return;
        }

        //Remove search keyword if access /du-issued-hist/list
        if ($this->getRequest()->isGet() && !isset($this->_input->page)) {
            $this->session->removeData(self::SESSION_KEY_SEARCH);
            $this->session->removeData(self::SESSION_CUSTOMER_NAME);
        }
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
        $page = null;
        $target = null;
        // In case user press search button to search
        if ($this->getRequest()->isPost()) {
            $customerCode = $this->_input->customer_code;

            $this->session->setModuleScope(self::SESSION_CUSTOMER_NAME, $this->_input->customer_name_hidden);
            $this->view->customer_name_hidden = $this->session->getData(self::SESSION_CUSTOMER_NAME);

            $where = $this->_input->getEscaped();
            $where['customer_code'] = $customerCode;
            if(isset($this->getRequest()->control_number) && !isset($where['control_number'])){
                $where = array();
            }

            if((isset($where['create_to_year']) || isset($where['create_to_month']) || isset($where['create_to_day'])) && (!isset($where['create_to']))){
                $where = array();
            }
            if((isset($where['create_from_year']) || isset($where['create_from_month']) || isset($where['create_from_day'])) && (!isset($where['create_from']))){
                $where = array();
            }

        } else {
            // Get search condition from session (if any), in case of access /du-issued-hist/list?page=x
            $where = $this->session->getData(self::SESSION_KEY_SEARCH);
            $customerCode = $this->_input->customer_code;
            $this->view->customer_name_hidden = $this->session->getData(self::SESSION_CUSTOMER_NAME);
            // If no search condition, set an empty array
            if (is_null($where)) {
                $where = array();
            }
            $page = isset($this->_input->page) ? $this->_input->page : $this->session->getData(self::SESSION_KEY_PAGE);
        }

        $this->getRequest()->setParams($where);
        $this->session->setModuleScope(self::SESSION_KEY_SEARCH, $where);
        $this->session->setModuleScope(self::SESSION_KEY_USER_ID, $where);
        $this->session->setModuleScope(self::SESSION_KEY_PAGE, $page);


        $t_publish_info = TPublishInfos::getInstance();
        $select = $t_publish_info->getDAIssuedList($where);
        if (isset($customerCode)) {
            $this->view->customer = MCustomers::getInstance()->getEntryByCode($customerCode);
        }
        $array = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
        foreach ($array as $item) {
            $arrayProduct = $t_publish_info->getListSelectProduct($item->control_number, '', $item->publish_type);
            $item->setProducts($arrayProduct);
            $detail = TPublishInfos::getInstance()->getRow($item->control_number, $item->publish_type);
            $this->session->setModuleScope(self::SESSION_KEY_DETAIL, $detail);
        }
        $this->view->paginator = $array;

        $this->view->not_issue = TPublishInfos::STATUS_NOT_ISSUE;
        $this->view->complete_issue = TPublishInfos::STATUS_COMPLETE_ISSUE;
        $this->view->paper = TPublishInfos::PUBLISH_TYPE_PAPER;
        $this->view->pdf = TPublishInfos::PUBLISH_TYPE_PDF;
        $this->view->max_display = TPublishInfos::MAX_DISPPLAY_ITEM;

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number' . ';End action');
    }

    public function addCustomerAction() {
        $customerCode = $this->_input->customer_code;
        $cookie = new Zynas_CookieManager();
        $cookie->setData($customerCode, self::COOKIE_CUSTOMER_CODE, '', 30, false);
    }

    public function getcustomerAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $cookie = new Zynas_CookieManager();
        if ($cookie->getData(self::COOKIE_CUSTOMER_CODE)) {
            $customerCode = $cookie->getData(self::COOKIE_CUSTOMER_CODE);
            $customer = MCustomers::getInstance()->getEntryByCode($customerCode);
            $customerJson = Zend_Json::encode(array('customer_name' => $customer->customer_name, 'customer_code' => $customer->customer_code));
            echo $customerJson;
            $cookie->removeData(self::COOKIE_CUSTOMER_CODE);
            return;
        } else {
            return;
        }
    }
    public function handleErrorList() {
    }
}

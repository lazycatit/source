<?php

class DiscoIssueController extends Controller {

    const URL_ROOT_UPLOAD = '/var/parameterSheet/';
    const URL_TEMPL_UPLOAD = '/var/temp/';
    const MANAGE_NUMBER1 = 'A';
    const MANAGE_NUMBER3 = '_';
    const DAY_START_IN_MONTH = '01';
    const DAY_FINISH_IN_MONTH = '31';
    const CATEGORY_DEVICE = '01';//装置
    const CATEGORY_SUBDEVICE = '02';//部品/ソフト
    const CATEGORY_PWP = '03';//ブレード/ホイール/Pad

    const CHOOSE_DEFAULT_COMBOBOX = '00';
    const ISSUE_PRODUCT= '1';
    const ISSUE_SERIES = '2';
    const SESSION_DEVICES = 'DEVICES';
    const SESSION_SUBDEVICES = 'SUBDEVICES';
    const SESSION_ISSUEPWPS = 'ISSUEPWPS';

    /**
     * 初期表示のアクション
     * <pre>
     * 1)一覧表示アクションにフォワードする
     * </pre>
     */
    public function indexAction() {

    }

    /**
     *
     * <pre>
     *
     *
     * </pre>
     */

    public function requestAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $MIssue = new MIssues();
        $MCategory = new MCategorys();
        $MCustomer = new MCustomers();
        $MUser = new MUsers();

        $userId = Auth_Info::getUser()->user_id;
        $this->view->user = MUsers::getInstance()->getUserByUserId($userId);

        if ($this->getRequest()->isGet()) {
            if(isset($this->_input->customer_code)){
                $customer_code = $this->_input->customer_code;
            }
        }

        if(!empty($customer_code))
        $this->view->customer = $MCustomer -> getEntryByCode($customer_code);

        $this->view->slProductCategory = $MCategory -> getSelectBoxCategoryAdmin('00');
        $this->view->slProductNumber = $MIssue -> getSelectBoxProductNumber(self::CHOOSE_DEFAULT_COMBOBOX,'01');
        $this->view->slIssueBy = $MIssue -> getSelectBoxModelNumber(self::CHOOSE_DEFAULT_COMBOBOX);
        $this->view->slSeries = $MCategory -> getSubCategoryPWPSelectBySerial(self::CHOOSE_DEFAULT_COMBOBOX);
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    public function nextAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $this->view->token = Csrf::getToken();

        $url_uploadfile = APPLICATION_PATH . "";
        $this->view->pdf = $this->_input->pdf;
        $this->view->paper = $this->_input->paper;
        $this->view->txtDivisionName = $this->_input->txtDivisionName;
        $this->view->txtStaffName = $this->_input->txtStaffName;
        $this->view->txtCustomerDesignation = $this->_input->txtCustomerDesignation;
        $this->view->export_person = $this->_input->export_person;

        $product_category = $this->view->product_category = $this->_input->product_category;
        $bf_opi_product_number = $this->view->slModel = $this->_input->slModel;
        $txt_product_number = $this->view->txt_product_number = $this->_input->txtProductNumber;
        $select_product_number = $this->view->select_product_number = $this->_input->product_number;
        $selectTypePwp = $this->view->selectTypePwp = $this->_input->selectTypePwp;
        $txt_m_product_number = $this->view->txt_m_product_number = $this->_input->txt_m_product_number;
        $select_category = $this->view->select_category = $this->_input->select_category;

        $bf_opi_serial_number = $this->view->bf_opi_serial_number = $this->_input->bf_opi_serial_number;

        if(!is_dir(APPLICATION_PATH."/var/parameterSheet/")){
            mkdir(APPLICATION_PATH."/var/parameterSheet/");
        }
        if(!is_dir(APPLICATION_PATH."/var/temp/")){
            mkdir(APPLICATION_PATH."/var/temp/");
        }
            //------process upload-------//
        if(isset($_FILES['file_upload']['tmp_name'])){
            $arr_file_upload = MIssues::getInstance()->getArrayUploadFile($_FILES['file_upload']['name']);

            $this->view->arr_file_upload = $arr_file_upload;
            $this->session->setModuleScope('ARR_FILE_UPLOAD',$arr_file_upload);

            $adapter = new Zend_File_Transfer_Adapter_Http();
            $adapter->setDestination(APPLICATION_PATH.(self::URL_TEMPL_UPLOAD));

            if (!$adapter->receive()) {
                $messages = $adapter->getMessages();
                echo implode("\n", $messages);
            }
        }

        /**
         * Process request issue
         *
         */
        $mBeforeOpinions = MBeforeOpinions::getInstance();
        $mProducts = MProducts::getInstance();
        $issueDevices = array();
        $issueSubDevices = array();
        $issuePWPs = array();

        if(!empty($product_category)){
            foreach ($product_category as $key => $value) {
                 if(strcmp($value, self::CATEGORY_DEVICE)===0){//装置
                    array_push($issueDevices,array($value,$bf_opi_product_number[$key],$bf_opi_serial_number[$key]));
                 }elseif (strcmp($value, self::CATEGORY_SUBDEVICE)===0) {//部品/ソフト
                    array_push($issueSubDevices, array($value,$txt_product_number[$key]));
                }elseif (strcmp($value, self::CATEGORY_PWP)===0) {//ブレード/ホイール/Pad
                    if(strcmp($selectTypePwp[$key], '1')==0){  //model
                        array_push($issuePWPs,array($value,$txt_m_product_number[$key],null,self::ISSUE_PRODUCT));
                    }else if(strcmp($selectTypePwp[$key], '2')===0){ //serial
                        array_push($issuePWPs,array($value,null,$select_category[$key],self::ISSUE_SERIES));
                    }else{
                        return false;
                    }
                }else {
                    return false;
                }
            }
        }

        $this->view->issueDevices = $issueDevices;
        $this->view->issueSubDevices = $issueSubDevices;
        $this->view->issuePWPs = $issuePWPs;

        $this->session->setModuleScope(self::SESSION_DEVICES, $issueDevices);
        $this->session->setModuleScope(self::SESSION_SUBDEVICES, $issueSubDevices);
        $this->session->setModuleScope(self::SESSION_ISSUEPWPS, $issuePWPs);

        $MIssue = MIssues::getInstance();
        $checkAutoIssue = true;
        if(!empty($issueDevices)){
            for($row=0;$row<count($issueDevices);$row++){
               if(!$MIssue->canAutoIssueDevices($issueDevices[$row]))
               $checkAutoIssue = false;
            }
        }

        if(!empty($issueSubDevices)){
            for($row=0;$row<count($issueSubDevices);$row++){
                if(!$MIssue->canAutoIssueSubDevices($issueSubDevices[$row]))
                $checkAutoIssue = false;
            }
        }

        if(!$checkAutoIssue){
            $this->view->autoIssue = false;
            return false;
        }else{
            $this->view->autoIssue = true;
            return true;
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    public function confirmNextAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $MIssue = MIssues::getInstance();
        $MProduct = MProducts::getInstance();
        $MCategory = MCategorys::getInstance();

        $tPublishProductInfos = TPublishProductInfos::getInstance();
        $pdf = $this->_input->pdf;
        $paper = $this->_input->paper;

        $issueDevices = $this->session->getData('DEVICES');
        $issueSubDevices = $this->session->getData('SUBDEVICES');
        $issuePWPs = $this->session->getData('ISSUEPWPS');

        /*
         * Get Manage No folder upload
         * */
        $date_ = Zynas_Date::dbDatetime();
        $manageNo_2 = substr($date_,2,2).substr($date_,5,2);
        $manageNo_4 = TPublishInfos::getInstance()->getManageNoFolder(null,substr($date_,0,8).(self::DAY_START_IN_MONTH),substr($date_,0,8).(self::DAY_FINISH_IN_MONTH));
        $manageNoFolder = (self::MANAGE_NUMBER1).$manageNo_2.(self::MANAGE_NUMBER3).$manageNo_4;
        $folderUpload = APPLICATION_PATH.(self::URL_ROOT_UPLOAD).$manageNoFolder."/";

        if(!is_dir(APPLICATION_PATH."/var/parameterSheet/")){
            mkdir(APPLICATION_PATH."/var/parameterSheet/");
        }
        if(!is_dir(APPLICATION_PATH."/var/temp/")){
            mkdir(APPLICATION_PATH."/var/temp/");
        }

        if(!is_dir($folderUpload)){
            mkdir($folderUpload);
            mkdir($folderUpload.'customer/');
        }//------end get Manage No folder upload--------//

        $arr_file_upload = $this->session->getData('ARR_FILE_UPLOAD');
        if(!empty($arr_file_upload)){
            foreach ($arr_file_upload as $file) {
                if (copy(APPLICATION_PATH.(self::URL_TEMPL_UPLOAD).$file, $folderUpload.'customer/'.$file)) {
                    unlink(APPLICATION_PATH.(self::URL_TEMPL_UPLOAD).$file);
                }
            }
        }
        $this->session->removeData('ARR_FILE_UPLOAD');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }


    /**
     * Non- auto issue
     */
    public function completeNonAutoAction() {

    }

    /**
     * Auto issue
     */
    public function completeAction() {

    }

    /**
     *ID取得失敗時の表示処理
     * <pre>
     * </pre>
     */

    public function handleErrorNext() {
//        FlashMessenger::addError('customer_code not exist');
    }
}

?>
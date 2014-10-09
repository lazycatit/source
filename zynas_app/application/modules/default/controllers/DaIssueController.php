<?php

class DaIssueController extends Controller {

    const SESSION_MAIN_CATEGORY = "MAIN_CATEGORY";
    const SESSION_PRODUCT_NUMBER = "PRODUCT_NUMBER";
    const SESSION_CATEGORY_NAME2 = "CATEGORY_NAME2";
    const SESSION_ISSUE_REQUEST = "ISSUE_REQUEST";
    const SESSION_ISSUE_CONTROL_NUMBER = "ISSUE_CONTROL_NUMBER";
    const FOLDER_PARAMETER_SHEET = "/var/parameterSheet/";
    const COOKIE_ORDER_PRODUCT = "ORDER_PRODUCT";
    const COOKIE_CUSTOMER_CODE = "CUSTOMER_CODE";

    public function indexAction() {
        // TODO Auto-generated FormsController::indexAction() default action
        $this->_redirect("/da-issue/request");
    }

    public function requestAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $issueRequest = new DuIssueRequest(null, null, null, array('postal_unit' => $user->unit_name, 'postal_name' => $user->name_jp));

        $issue = $this->session->getData(self::SESSION_ISSUE_REQUEST);
        if ($issue) {
            $this->view->issue_request = $issue;
        } else {
            $this->view->issue_request = $issueRequest;
        }

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function newproductAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('newproduct', 'html')->initContext();
        $id = $this->_getParam('index', null);
        $issueProduct = new IssueProduct($id);
        $this->view->issue_product = $issueProduct;

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function newmailccAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('newmailcc', 'html')->initContext();
        $id = $this->_getParam('index', null);
        $this->view->id_cc = $id;

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function newfileAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('newfile', 'html')->initContext();
        $id = $this->_getParam('index', null);
        $this->view->index = $id;

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function confirmNextAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $issueRequest = new DuIssueRequest();
        $issueRequest = $this->session->getData(self::SESSION_ISSUE_REQUEST);
        $canAutoIssue = $issueRequest->checkAutoIssue();
        //Create a control number
        $currentDate = date('ym');
        $nextControlNumber = 0;
        $mControlNumberTable = MControlNumbers::getInstance();
        $mControlNumberTable->lockTable();
        $currentDbControlNumber = $mControlNumberTable->getControlNumber($currentDate);
        if (!empty($currentDbControlNumber)) {
            $nextControlNumber = $currentDbControlNumber->control_number;
        }
        $nextControlNumber++;

        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $newControlNumber = $mControlNumberTable->createRow();
            $newControlNumber->control_yymm = $currentDate;
            $newControlNumber->control_number = $nextControlNumber;
            $newControlNumber->create_date = Zynas_Date::dbDatetime();
            $newControlNumber->create_user = Auth_Info::getUser()->user_id;
            $newControlNumber->save();
            $db->commit();
        } catch (Exception $ex) {
            $db->rollBack();
            $e = new Zynas_Exception();
            $e->setErrors($ex->__toString());
            throw $e;
        }
        $mControlNumberTable->unlockTable();

        $assembledControlNumber = 'A' . $currentDate . '_' . sprintf('%04d', $nextControlNumber);
        //Create the folder which contain parameter sheet
        if (!is_dir(APPLICATION_PATH . self::FOLDER_PARAMETER_SHEET)) {
            FileManager::createDirectory(APPLICATION_PATH . self::FOLDER_PARAMETER_SHEET);
        }
        if (!is_dir(APPLICATION_PATH . self::FOLDER_PARAMETER_SHEET . date('Ym') . '/')) {
            FileManager::createDirectory(APPLICATION_PATH . self::FOLDER_PARAMETER_SHEET . date('Ym') . '/');
        }
        $folderUpload = APPLICATION_PATH . self::FOLDER_PARAMETER_SHEET . date('Ym') . '/' . $assembledControlNumber;
        if (!is_dir($folderUpload)) {
            FileManager::createDirectory($folderUpload);
        }
        //Copy customer file from temporary folder to parameterSheet/control_number/customer folder
        $arrayFileUpload = $issueRequest->customerFile;
        if (!empty($arrayFileUpload)) {
            for ($j = 0; $j < count($arrayFileUpload); $j++) {
                if (strcmp($j, '0') === 0) {
                    FileManager::copyFile($arrayFileUpload[0]['fileName'], $arrayFileUpload[0]['tempFolder'], $folderUpload . '/customer/');
                }
                if (strcmp($j, '1') === 0) {
                    FileManager::copyFile($arrayFileUpload[1]['fileName'], $arrayFileUpload[1]['tempFolder'], $folderUpload . '/customer/');
                }
                if (strcmp($j, '2') === 0) {
                    FileManager::copyFile($arrayFileUpload[2]['fileName'], $arrayFileUpload[2]['tempFolder'], $folderUpload . '/customer/');
                }
            }
            //Remove temporary folder
            $success = FileManager::deleteDirAndFile($arrayFileUpload[0]['tempFolder']);
            if (!$success) {
                throw new Zynas_Exception('Can not delete folder:' . $arrayFileUpload[0]['tempFolder']);
            }
        }
        $customer = MCustomers::getInstance()->getEntryByCode($issueRequest->customer['customer_code']);
        //Save database here
        if (strcmp($issueRequest->ckPdf, '1') === 0) {
            $db = Zynas_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $row = TPublishInfos::getInstance()->createRow();
                $row->create_date = Zynas_Date::dbDatetime();
                $row->create_user = Auth_Info::getUser()->user_id;
                $row->control_number = $assembledControlNumber;
                if ($issueRequest->isCustomerDesignation()) {
                    $row->is_coustomer_designation = 1;
                    $row->coustomer_designation = $issueRequest->getCustomerDesignationText();
                } else {
                    $row->is_coustomer_designation = 0;
                }
                if (!empty($issueRequest->customerFile)) {
                    $row->is_coustomer_format = 1;
                } else {
                    $row->is_coustomer_format = 0;
                }
                if ($issueRequest->isCustomerFormat()) {
                    $row->export_person = 2;
                } else {
                    $row->export_person = 1;
                }
                $arrayFileUpload = $issueRequest->customerFile;
                if (!empty($arrayFileUpload)) {
                    for ($j = 0; $j < count($arrayFileUpload); $j++) {
                        if (strcmp($j, 0) === 0) {
                            $row->coustomer_format1 = $arrayFileUpload[0]['fileName'];
                        }
                        if (strcmp($j, 1) === 0) {
                            $row->coustomer_format2 = $arrayFileUpload[1]['fileName'];
                        }
                        if (strcmp($j, 2) === 0) {
                            $row->coustomer_format3 = $arrayFileUpload[2]['fileName'];
                        }
                    }
                }
                $mailcc = $this->_input->text_email_cc;
                if (!empty($mailcc) && strcmp($mailcc[0], '') !== 0) {
                    for ($i = 0; $i < count($mailcc); $i++) {
                        if (strcmp($i, 0) === 0)
                            $row->send_cc_mail1 = $mailcc[0];
                        if (strcmp($i, 1) === 0)
                            $row->send_cc_mail2 = $mailcc[1];
                        if (strcmp($i, 2) === 0)
                            $row->send_cc_mail3 = $mailcc[2];
                    }
                }
                $row->customer_code = isset($this->_input->customer_code) ? $this->_input->customer_code : isset($customer->customer_code) ? $customer->customer_code : '';
                $row->customer_name = $customer->customer_name;
                // $row->send_customer_name = isset($this->_input->customer_name) ? $this->_input->customer_name : $customer->customer_name;
                // $row->send_postal_code = isset($this->_input->postal_code) ? $this->_input->postal_code : $customer->postal_code;
                // $row->send_address = isset($this->_input->customer_address) ? $this->_input->customer_address : $customer->address1 . $customer->address2 . $customer->address3 . $customer->address4;
                // $row->send_tel = isset($this->_input->customer_tel) ? $this->_input->customer_tel : $customer->tel;
                // $row->send_mail = isset($this->_input->user_mail) ? $this->_input->user_mail : Auth_Info::getUser()->email;
                // $row->send_user_name = isset($this->_input->user_name) ? $this->_input->user_name : Auth_Info::getUser()->name_jp;
                // $row->send_unit_name = isset($this->_input->unit_name) ? $this->_input->unit_name : Auth_Info::getUser()->unit_name;
                //$row->send_office_user_name = $issueRequest->postalInfo['postal_name'];
                //$row->send_office_unit_name = $issueRequest->postalInfo['postal_unit'];
                if ($canAutoIssue && !$issueRequest->isCustomerDesignation() && !$issueRequest->isCustomerFormat())
                    $row->status = TPublishInfos::STATUS_COMPLETE_ISSUE;
                else {
                    $row->status = TPublishInfos::STATUS_NOT_ISSUE;
                }
                //Check doOption
                if ($canAutoIssue) {
                    $row->do_option = null;
                } else {
                    $row->do_option = 0;
                }
                if (!empty($arrayFileUpload)) {
                    $row->do_coustomer_format = 0;
                } else {
                    $row->do_coustomer_format = null;
                }
                if ($issueRequest->isCustomerDesignation()) {
                    $row->do_coustomer_designation = 0;
                } else {
                    $row->do_coustomer_designation = null;
                }
                $row->do_base_paper = null;

                $row->publish_type = 1;
                //Write a record to T_Publish_Hist
                PublishHistCreator::createFromRow($row)->save();
                $row->save();
                $db->commit();
            } catch (Exception $ex) {
                $db->rollBack();
                $e = new Zynas_Exception();
                $e->setErrors($ex->__toString());
                throw new Zynas_Exception($ex->__toString());
            }
        }

        if (strcmp($issueRequest->ckPaper, '1') === 0) {
            $db = Zynas_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $row = TPublishInfos::getInstance()->createRow();
                $row->create_date = Zynas_Date::dbDatetime();
                $row->create_user = Auth_Info::getUser()->user_id;
                $row->control_number = $assembledControlNumber;
                if ($issueRequest->isCustomerDesignation()) {
                    $row->is_coustomer_designation = 1;
                    $row->coustomer_designation = $issueRequest->getCustomerDesignationText();
                } else {
                    $row->is_coustomer_designation = 0;
                }
                if (!empty($issueRequest->customerFile)) {
                    $row->is_coustomer_format = 1;
                } else {
                    $row->is_coustomer_format = 0;
                }
                if ($issueRequest->isCustomerFormat()) {
                    $row->export_person = 2;
                } else {
                    $row->export_person = 1;
                }
                $arrayFileUpload = $issueRequest->customerFile;
                if (!empty($arrayFileUpload)) {
                    for ($j = 0; $j < count($arrayFileUpload); $j++) {
                        if (strcmp($j, 0) === 0) {
                            $row->coustomer_format1 = $arrayFileUpload[0]['fileName'];
                        }
                        if (strcmp($j, 1) === 0) {
                            $row->coustomer_format2 = $arrayFileUpload[1]['fileName'];
                        }
                        if (strcmp($j, 2) === 0) {
                            $row->coustomer_format3 = $arrayFileUpload[2]['fileName'];
                        }
                    }
                }
                $mailcc = $this->_input->text_email_cc;
                if (!empty($mailcc) && strcmp($mailcc[0], '') !== 0) {
                    for ($i = 0; $i < count($mailcc); $i++) {
                        if (strcmp($i, 0) === 0)
                            $row->send_cc_mail1 = $mailcc[0];
                        if (strcmp($i, 1) === 0)
                            $row->send_cc_mail2 = $mailcc[1];
                        if (strcmp($i, 2) === 0)
                            $row->send_cc_mail3 = $mailcc[2];
                    }
                }
                $row->customer_code = isset($this->_input->customer_code) ? $this->_input->customer_code : isset($customer->customer_code) ? $customer->customer_code : '';
                $row->customer_name = $customer->customer_name;
                // $row->send_customer_name = isset($this->_input->customer_name) ? $this->_input->customer_name : $customer->customer_name;
                // $row->send_postal_code = isset($this->_input->postal_code) ? $this->_input->postal_code : $customer->postal_code;
                // $row->send_address = isset($this->_input->customer_address) ? $this->_input->customer_address : $customer->address1 . $customer->address2 . $customer->address3 . $customer->address4;
                // $row->send_tel = isset($this->_input->customer_tel) ? $this->_input->customer_tel : $customer->tel;
                // $row->send_mail = isset($this->_input->user_mail) ? $this->_input->user_mail : Auth_Info::getUser()->email;
                // $row->send_user_name = isset($this->_input->user_name) ? $this->_input->user_name : Auth_Info::getUser()->name_jp;
                // $row->send_unit_name = isset($this->_input->unit_name) ? $this->_input->unit_name : Auth_Info::getUser()->unit_name;
                $row->send_office_user_name = $issueRequest->postalInfo['postal_name'];
                $row->send_office_unit_name = $issueRequest->postalInfo['postal_unit'];
                $row->status = TPublishInfos::STATUS_NOT_ISSUE;
                //Check doOption
                if ($canAutoIssue) {
                    $row->do_option = null;
                } else {
                    $row->do_option = 0;
                }
                if (!empty($arrayFileUpload)) {
                    $row->do_coustomer_format = 0;
                } else {
                    $row->do_coustomer_format = null;
                }
                if ($issueRequest->isCustomerDesignation()) {
                    $row->do_coustomer_designation = 0;
                } else {
                    $row->do_coustomer_designation = null;
                }
                $row->do_base_paper = 0;

                $row->publish_type = 2;
                $row->save();
                //Write a record to T_Publish_Hist
                PublishHistCreator::createFromRow($row)->save();
                $db->commit();
            } catch (Exception $ex) {
                $db->rollBack();
                $e = new Zynas_Exception();
                $e->setErrors($ex->__toString());
                throw $e;
                FlashMessenger::addError($e->getMessage());
            }
        }
        // Write products
        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            //Write products to T_Publish_Product_Info
            foreach ($issueRequest->arrayIssueProducts as $index => $product) {
                $tPublishProductInfoRow = TPublishProductInfos::getInstance()->createRow();
                $tPublishProductInfoRow->control_number = $assembledControlNumber;
                $tPublishProductInfoRow->control_branch_number = $index;
                $tPublishProductInfoRow->product_category = $product->mainProductCategory;

                switch ($product->mainProductCategory) {
                    case MCategorys::CATEGORY_SUBDEVICE :
                        $productFromMProduct = MProducts::getInstance()->getProductByProductNumberView($product->productNumberView);
                        if (!empty($productFromMProduct)) {
                            $tPublishProductInfoRow->product_number = $productFromMProduct->product_number;
                        }
                        break;
                    case MCategorys::CATEGORY_DEVICE :
                        $tPublishProductInfoRow->product_number = $product->productNumber;
                        $tPublishProductInfoRow->serial_number = $product->serialNumber;
                        break;
                    case MCategorys::CATEGORY_PWP :
                        switch ($product->modelSerial) {
                            case '01' :
                                $tPublishProductInfoRow->product_number = $product->productNumberTxt;
                                break;
                            case '02' :
                                $tPublishProductInfoRow->category = $product->categoryName2;
                                $tPublishProductInfoRow->product_number = null;
                                break;
                            default :
                                break;
                        }
                    default :
                        break;
                }

                $tPublishProductInfoRow->product_name_view = $product->productNameView;
                $tPublishProductInfoRow->create_date = Zynas_Date::dbDatetime();
                $tPublishProductInfoRow->create_user = Auth_Info::getUser()->user_id;
                $tPublishProductInfoRow->save();
            }

            $db->commit();
        } catch (Exception $ex) {
            $db->rollBack();
            $e = new Zynas_Exception();
            $e->setErrors($ex->__toString());
            throw $e;
        }
        // Create Pdf file
        $createPdfError = 0;
        try {
            $pdfFileNamePdf = '';
            $pdfFileNamePaper = '';
            if (strcmp($issueRequest->ckPdf, '1') === 0 && $canAutoIssue && !$issueRequest->isCustomerDesignation() && !$issueRequest->isCustomerFormat()) {
                $pdfFileNamePdf = Pdf_Connector::callSync($assembledControlNumber, TPublishInfos::PUBLISH_TYPE_PDF);
                $db = Zynas_Db_Table::getDefaultAdapter();
                $db->beginTransaction();
                try {
                    $tPublishInfoRow = TPublishInfos::getInstance()->getRow($assembledControlNumber, TPublishInfos::PUBLISH_TYPE_PDF);
                    $tPublishInfoRow->status = TPublishInfos::STATUS_COMPLETE_ISSUE;
                    $tPublishInfoRow->sysmtem_parameter_sheet = $pdfFileNamePdf;
                    $tPublishInfoRow->save();
                    $db->commit();
                } catch (Exception $ex) {
                    $db->rollBack();
                    $e = new Zynas_Exception();
                    $e->setErrors($ex->__toString());
                    throw $e;
                }

            } elseif (strcmp($issueRequest->ckPdf, '1') === 0 && !($canAutoIssue)) {
                Pdf_Connector::callASync($assembledControlNumber, TPublishInfos::PUBLISH_TYPE_PDF);
            }
            if (strcmp($issueRequest->ckPaper, '1') === 0) {
                Pdf_Connector::callASync($assembledControlNumber, TPublishInfos::PUBLISH_TYPE_PAPER);
            }

        } catch(Pdf_Exception $ex) {
            $createPdfError = 1;
            $e = new Zynas_Exception();
            $e->setErrors($ex->__toString());
            throw $e;
        }

        /* Send email source code - skip this step for debug*/
        //Send email source code - skip this step for debug
        //Send email to login DU
        $data = array('name' => array('{$control_number}', '{$contact}'), 'value' => array($assembledControlNumber, Zynas_Registry::getConfig()->constants->contact_text));
        Mail::getInstance('receipt_issue', $this->view)->send(Auth_Info::getUser()->user_id, Auth_Info::getUser()->name_jp, null, null, $data);
        //Send email to DA
        $emailDA = Zynas_Registry::getConfig()->system->mail->admin_to_email;
        $nameDA = Zynas_Registry::getConfig()->system->mail->admin_to_name;
        Mail::getInstance('receipt_issue', $this->view)->send($emailDA, $nameDA, null, null, $data);
        // Send another email to DA
        $data = array('name' => array('{$control_number}', '{$fqdn}'), 'value' => array($assembledControlNumber, Zynas_Registry::getConfig()->system->fqdn));
        Mail::getInstance('notify_receipt_to_admin', $this->view)->send($emailDA, $nameDA, null, null, $data);

        //Send email to admin if m_summary_before_opinion.result =1
        $sendMail = $issueRequest->checkNeededSendMail();
        if ($sendMail) {
            $data = array('name' => array('{$control_number}', '{$fqdn}'), 'value' => array($assembledControlNumber, Zynas_Registry::getConfig()->system->fqdn));
            $emailDA = Zynas_Registry::getConfig()->system->mail->admin_to_email;
            $nameDA = Zynas_Registry::getConfig()->system->mail->admin_to_name;
            Mail::getInstance('notify_fall_under_to_admin', $this->view)->send($emailDA, $toName, $nameDA, null, $data);
        }
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
        
        if ($createPdfError == 1) {
            $this->redirect('/da-issue/nonauto-issue');
            return;
        }
        if (strcmp($issueRequest->ckPdf, '1') === 0 && ($canAutoIssue) && empty($issueRequest->customerFile) && !$issueRequest->isCustomerDesignation()) {
            $this->session->setModuleScope(self::SESSION_ISSUE_CONTROL_NUMBER, $assembledControlNumber);
            $this->redirect('/da-issue/auto-issue');
            return;
        } else {
            $this->session->setModuleScope(self::SESSION_ISSUE_CONTROL_NUMBER, $assembledControlNumber);
            $this->redirect('/da-issue/nonauto-issue');
            return;
        }

    }

    public function autoIssueAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $controlNumber = $this->session->getData(self::SESSION_ISSUE_CONTROL_NUMBER);
        $this->session->removeData(self::SESSION_ISSUE_REQUEST);
        $this->session->removeData(self::SESSION_ISSUE_CONTROL_NUMBER);
        $tPublishInfoRow = TPublishInfos::getInstance()->getRow($controlNumber, TPublishInfos::PUBLISH_TYPE_PDF);
        $tPublishInfoRowPaper = TPublishInfos::getInstance()->getRow($controlNumber, TPublishInfos::PUBLISH_TYPE_PAPER);
        $this->view->row = $tPublishInfoRow;
        $this->view->rowPaper = $tPublishInfoRowPaper;
        $this->view->contact = Zynas_Registry::getConfig()->constants->contact;
//        echo 'Control number:'.$controlNumber;
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function nonautoIssueAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $this->session->removeData(self::SESSION_ISSUE_REQUEST);
        $controlNumber = $this->session->getData(self::SESSION_ISSUE_CONTROL_NUMBER);
        $this->session->removeData(self::SESSION_ISSUE_CONTROL_NUMBER);
//        echo 'Control number:'.$controlNumber;
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function orderAddAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $arrOrderId = $this->_input->select;
        $cookie = new Zynas_CookieManager();
        $cookie->setData(Zend_Json::encode($arrOrderId), self::COOKIE_ORDER_PRODUCT, '', 30, false);

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function addorderproductAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $cookie = new Zynas_CookieManager();
        if ($cookie->getData(self::COOKIE_ORDER_PRODUCT)) {
            $cookie->removeData(self::COOKIE_ORDER_PRODUCT);
            $jsonOrderProductData = $cookie->getData(self::COOKIE_ORDER_PRODUCT);
            $arrayOrderProductData = Zend_Json::decode($jsonOrderProductData);
            $ajaxContext = $this->_helper->getHelper('AjaxContext');
            $ajaxContext->addActionContext('addorderproduct', 'html')->initContext();
            $id = $this->_getParam('index', null);
            $noProduct = $this->_getParam('noproduct', null);
            $arrayIssueProduct = array();
            foreach ($arrayOrderProductData as $key => $orderProductId) {
                $tOrderRow = TOrders::getInstance()->getEntryById($orderProductId);
                if (!empty($tOrderRow)) {
                    $mProductRow = MProducts::getInstance()->getProduct($tOrderRow->product_number);
                    if (!empty($mProductRow)) {

                        switch ($mProductRow->product_category) {
                            case MCategorys::CATEGORY_DEVICE :
                                $issueProduct = new IssueProduct($id + $key, MCategorys::CATEGORY_DEVICE, $tOrderRow->product_number, isset($tOrderRow->serial_number) ? $tOrderRow->serial_number : '');
                                $arrayIssueProduct[] = $issueProduct;
                                break;
                            case MCategorys::CATEGORY_SUBDEVICE :
                                $issueProduct = new IssueProduct($id + $key, MCategorys::CATEGORY_SUBDEVICE, null, null, isset($mProductRow->product_number_view) ? $mProductRow->product_number_view : '');
                                $arrayIssueProduct[] = $issueProduct;
                                break;
                            case MCategorys::CATEGORY_PWP :
                                $issueProduct = new IssueProduct($id + $key, MCategorys::CATEGORY_PWP, null, null, null, '01', $mProductRow->product_number);
                                $arrayIssueProduct[] = $issueProduct;
                                break;
                            default :
                                break;
                        }
                    }
                }
            }

            $this->view->arrOrderProduct = $arrayIssueProduct;
            $this->view->newNoProduct = $noProduct + count($arrayOrderProductData);
            $this->view->currentProductIndex = $id;
        } else {
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
        }

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function downloadAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isGet()) {
            $fileName = $this->_input->filename;
            $controlNumber = $this->_input->controlnumber;
            $tPublishInfoRow = TPublishInfos::getInstance()->getRow($controlNumber, TPublishInfos::PUBLISH_TYPE_PDF);
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $tPublishInfoRow->create_date);
            $yymm = $date->format("Ym");
            $tfile = APPLICATION_PATH . self::FOLDER_PARAMETER_SHEET . $yymm . "/" . $controlNumber . "/" . $fileName;
            // Set header for each type of files
            header("Content-Type: application/pdf");
            $tp = pathinfo($tfile);

            header("Content-Length: " . filesize($tfile));

            header('Content-Transfer-Encoding: Binary');
            header('Accept-Ranges: bytes');

            header('ETag: "' . md5($tfile) . '"');
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");

            FileManager::readfileChunked($tfile);
        }

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function handleErrorRequest() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');

    }

    public function confirmRequestAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        if (!empty($this->view->errors)) {
            function findErrorProduct($field) {
                if (strpos($field, 'select_main_product_category_') !== false || strpos($field, 'select_product_number_') !== false || strpos($field, 'text_serial_number_') !== false || strpos($field, 'text_product_number_view_') !== false || strpos($field, 'select_model_serial_') !== false || strpos($field, 'text_product_number_') !== false || strpos($field, 'select_category_name2_') !== false) {
                    return $field;
                }
            }

            $errors = $this->view->errors;
            $fields = array_filter(array_keys($errors), 'findErrorProduct');
            if ($fields) {
                $errors['product_error'] = "Product invalid";
                $this->view->errors = $errors;
            }
        }
        $adapter = new Zend_File_Transfer_Adapter_Http();
        $files = $adapter->getFileInfo();
        if (!empty($files)) {
            $adapter->addValidator('Extension', false, Zynas_Registry::getConfig()->document->type);
            if (!$adapter->isValid()) {
                $errors = $this->view->errors;
                $errors['customer_file'] = E026V;
                $this->view->errors = $errors;
            } else {
                foreach ($files as $fieldname => $fileinfo) {
                    if (strlen($fileinfo['name']) > 255) {
                        $errors = $this->view->errors;
                        $errors['customer_file'] = E027V;
                        $this->view->errors = $errors;
                    }
                }
            }
        }

        if (!isset($this->view->errors) || empty($this->view->errors)) {
            if ($this->getRequest()->isPost()) {
                $issueRequest = new DuIssueRequest();
                // Form has been submitted - run data through preValidation()
                $issueRequest->preValidation($_POST);
                // Get file uploaded
                if (!is_dir(APPLICATION_PATH . "/var/tmp/")) {
                    FileManager::createDirectory(APPLICATION_PATH . "/var/tmp/");
                }
                if (!empty($files)) {
                    if ($adapter->isValid()) {
                        $currentDate = date('Ymdhisu') . '_' . microtime(true);
                        $tempFolder = APPLICATION_PATH . '/var/tmp/' . $currentDate . '_' . Auth_Info::getUser()->user_id . "/";
                        // Create a folder /var/tmp/[date_create_issue]_[creator_user_id]
                        FileManager::createDirectory($tempFolder);
                        $adapter->setDestination($tempFolder);
                        foreach ($files as $fieldname => $fileinfo) {
                            if (($adapter->isUploaded($fileinfo['name'])) && ($adapter->isValid($fileinfo['name']))) {
                                $issueRequest->customerFile[] = array('fileName' => $fileinfo['name'], 'tempFolder' => $tempFolder);
                                $adapter->receive($fileinfo['name']);
                            }
                        }
                    }
                }
                $this->session->setModuleScope(self::SESSION_ISSUE_REQUEST, $issueRequest);
                $this->_redirect("/da-issue/next");
            } else {
                $this->_redirect("/error");
            }
        } else {
            return $this->handleErrorConfirmRequest();
        }

        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function handleErrorConfirmRequest() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
    }

    public function nextAction() {
        $user = Auth_Info::getUser();
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';Start action');

        $customer = MCustomers::getInstance()->getEntryById(Auth_Info::getUser()->user_id);
        $this->view->customer = $customer;
        $this->view->user = Auth_Info::getUser();
        $issueRequest = new DuIssueRequest();
        $issueRequest = $this->session->getData(self::SESSION_ISSUE_REQUEST);
        $issueRequest->generateDisplayName();
        $this->view->issue_request = $issueRequest;
        $this->view->auto_issue = $issueRequest->checkAutoIssue();
        if ($this->view->auto_issue) {
            echo ' Can auto issue';
        } else {
            echo ' Can not auto issue';
        }
        Log::infoLog('method=' . __FUNCTION__ . ';user_id=' . $user->user_id . ';control_number=' . ';End action');
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

}

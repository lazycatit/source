<?php

class UpDownController extends Controller {
    
    const URL_ROOT_UPLOAD = '/var/parameterSheet/';
    const URL_TEMPL_UPLOAD = '/var/temp';
    const URL_CUSTOMER_FOLDER = '/customer';
    const FILE_NAME_PDF = '_customer_parameter_sheet';
    const FILE_NAME_BASE_PAPER = '_customer_parameter_sheet_base';
    const PDF_EXT = '.pdf';
    const SEPARATOR = '/';
    const SESSION_KEY_DETAIL = 'detail';
        
    /**
     * Upload file
     * @param string $path  
     * @return boolean 
     */
    private function uploadFile($path, $control_number, $publish_type) {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $newFileName = strcmp($publish_type, TPublishInfos::PUBLISH_TYPE_PDF) === 0 ? $control_number.self::FILE_NAME_PDF.self::PDF_EXT : $control_number.self::FILE_NAME_BASE_PAPER.self::PDF_EXT;
        $fullPath = $path.$newFileName;
        try {
            $adapter = new Zend_File_Transfer_Adapter_Http();
            $adapter->setDestination($path);
            $adapter->addFilter(new Zend_Filter_File_Rename(array(
                'target' =>  $fullPath,
                'overwrite' => true)
            ));
            $adapter->addValidator('Extension', false, Zynas_Registry::getConfig()->document->pdf);
            return ($adapter->isValid() && $adapter->receive()) ? true : false;
        } catch (Exception $e) {
            throw new Exception($e);
        }
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }
    
    /**
     * Check for status can change or not
     * 
     * @param int $do_option
     * @param int $do_customer_designation
     * @param int $do_customer_format
     * @param int $do_base_paper
     * @param string $file_upload
     * @return boolean
     */
    private function canChangeStatus($do_option, $do_customer_designation, $do_customer_format, $do_base_paper, $file_upload) {
        if ($do_option*$do_customer_designation*$do_customer_format*$do_base_paper !== 0
            || $this->_input->result == 1
            || (!empty($file_upload) && ($this->_input->ck_do_option != 0 || $this->_input->ck_do_option == NULL) && ($this->_input->ck_do_base_paper != 0 || $this->_input->ck_do_base_paper == NULL))
        ) {
            return true;
        }
        return false;
    }

    /**
     * Set data to one row in t_publish_info table
     * @param int $control_number
     * @param int $publish_type
     * @param int $do_option
     * @param int $do_base_paper
     * @param int $do_customer_designation
     * @param int $do_customer_format
     * @param string $file_upload
     * @return boolean
     */
    public function setRowData($control_number, $publish_type, $do_option, $do_base_paper, $do_customer_designation, $do_customer_format, $file_upload) {
        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        $status = false;
            
        try {
            $row = TPublishInfos::getInstance()->getRow($control_number, $publish_type);
            if ($this->canChangeStatus($do_option, $do_customer_designation, $do_customer_format, $do_base_paper, $file_upload)) {
                $row->status = TPublishInfos::STATUS_COMPLETE_ISSUE;
            }
            $row->do_option = strcmp($this->_input->ck_do_option, '') !== 0 ? $this->_input->ck_do_option : null;
            if (strcmp($file_upload, null) !== 0 && strcmp($do_customer_designation, TPublishInfos::CK_OFF) === 0) {
                $row->do_coustomer_designation = TPublishInfos::CK_ON;
            } elseif (strcmp($file_upload, null) === 0 && strcmp($do_customer_designation, TPublishInfos::CK_OFF) === 0) {
                $row->do_coustomer_designation = $this->_input->ck_do_customer_designation;
            }
            if (strcmp($file_upload, null) !== 0 && strcmp($do_customer_format, TPublishInfos::CK_OFF) === 0) {
                $row->do_coustomer_format = TPublishInfos::CK_ON;
            } elseif (strcmp($file_upload, null) === 0 && strcmp($do_customer_format, TPublishInfos::CK_OFF) === 0) {
                $row->do_coustomer_format = $this->_input->ck_do_customer_format;
            }
            $row->do_base_paper = strcmp($this->_input->ck_do_base_paper, '') !== 0 ? $this->_input->ck_do_base_paper : null;
            if (!empty($file_upload)) {
                $row->upload_parameter_sheet = $file_upload;
            }
    
            $row->update_date = Zynas_Date::dbDateTime();
            $row->update_user = Auth_Info::getUser()->user_id;
            $row->save();
            $db->commit();
            $status = true;
        } catch (Exception $e) {
            $db->rollBack();
            FlashMessenger::addError($e->getMessage());
        }
        return (bool)$status;
    }
    
    /**
     * Index action
     */
    public function indexAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        $this->_forward('/request');
    }
    
    /**
     * Request action
     * 
     */
    public function requestAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
                
        $id = isset($this->_input->id) ? $this->_input->id : '';
        $entry = TPublishInfos::getInstance()->getEntryById($id);
        $control_number = $entry->control_number;        
        $publish_type = $entry->publish_type;       
        $product_array = TPublishProductInfos::getInstance()->getProductsByControlNumber($control_number);
        
        //Get information detail
        $detail = TPublishInfos::getInstance()->getRow($control_number, $publish_type);
        $detail->setProducts($product_array);        
        $this->session->setModuleScope(self::SESSION_KEY_DETAIL, $detail);
        
        $this->view->issue = $detail;
        $this->view->paper = TPublishInfos::PUBLISH_TYPE_PAPER;
        $this->view->pdf = TPublishInfos::PUBLISH_TYPE_PDF; 
        $this->view->cust_form = TPublishInfos::IS_CUSTOMER_FORMAT;
        $this->view->not_cust_form = TPublishInfos::IS_NOT_CUSTOMER_FORMAT;  
        $this->view->control_number = $control_number;
        
        //to get customer_code and user_type from M_Customer 
        $this->view->m_user = MUsers::getInstance()->getUserByUserId($detail->create_user);
        $this->view->end_user = MUsers::END_USER;
        $this->view->disco_user = MUsers::DISCO_USER;
        $this->view->disco_admin = MUsers::DISCO_ADMIN;
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }
    
    /**
     * Process when submit data
     * Upload file, save data to database and send mail to annouce all users
     */
    public function confirmRequestAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $change_status_flag = '';
        $upload_status_flag = '';
        $set_data_flag = '';
        $id = isset($this->_input->id) ? $this->_input->id : '';
        $result = isset($this->_input->result) ? $this->_input->result : '';
        
        $detail = $this->session->getData(self::SESSION_KEY_DETAIL);
        $control_number = $detail->control_number;
        $publish_type = $detail->publish_type;
        $product_array = TPublishProductInfos::getInstance()->getProductsByControlNumber($control_number);
        $detail->setProducts($product_array);
        
        if (strcmp($result, '') !== 0) {
            if (strcmp($result, '1') !== 0 && $this->_input->status == '2') {
                $errors = $this->view->errors;
                $errors['status_error'] = E032V;
                $this->view->errors = $errors;  
                $change_status_flag = false;
            } else {
                $change_status_flag = true;
            }
        } else {
            $this->_redirect('/error');
        }
        
        if (strcmp($id, '') === 0) {            
            $this->_redirect('/error');
        }
        
        $this->view->issue = $detail;
        $this->view->m_user = MUsers::getInstance()->getUserByUserId($detail->create_user);
        $this->view->paper = TPublishInfos::PUBLISH_TYPE_PAPER;
        $this->view->pdf = TPublishInfos::PUBLISH_TYPE_PDF;
        $this->view->cust_form = TPublishInfos::IS_CUSTOMER_FORMAT;
        $this->view->not_cust_form = TPublishInfos::IS_NOT_CUSTOMER_FORMAT;
        $this->view->control_number = $control_number;
        $this->view->end_user = MUsers::END_USER;
        $this->view->disco_user = MUsers::DISCO_USER;
        $this->view->disco_admin = MUsers::DISCO_ADMIN;
        
        //process of upload and get path of upload file to save in database
        $yyyymm = str_replace('-' , '', substr($detail->create_date, 0, 7));
        if (isset($_FILES['file_upload']['name']) && !empty($_FILES['file_upload']['name'])) {
            $path = APPLICATION_PATH.self::URL_ROOT_UPLOAD.$yyyymm.self::SEPARATOR.$control_number.self::SEPARATOR;            
            if (!$this->uploadFile($path, $control_number, $publish_type)) {
                $errors = $this->view->errors;
                $errors['file_upload_error'] = E026V;
                $this->view->errors = $errors;                    
                $upload_status_flag = false;                    
            } else {
                $upload_status_flag = true;
            }           
            //set file_path to save in database
            $file_upload = strcmp($publish_type, TPublishInfos::PUBLISH_TYPE_PDF) === 0 ? $control_number.self::FILE_NAME_PDF.self::PDF_EXT : $control_number.self::FILE_NAME_BASE_PAPER.self::PDF_EXT;                       
        } else { 
            $upload_status_flag = true;
            $file_upload = '';
        }
                
        //process of saving data to database
        $do_option = $detail->do_option;
        $do_base_paper = $detail->do_base_paper;
        $do_customer_designation = $detail->do_coustomer_designation;
        $do_customer_format = $detail->do_coustomer_format;
        $publish_type = $this->_input->publish_type;
                
        if ($change_status_flag && $upload_status_flag) {            
            //Set data to TPublishInfo
            if ($this->setRowData($control_number, $publish_type, $do_option, $do_base_paper, $do_customer_designation, $do_customer_format, $file_upload)) {
                //Set data to TPublishHist
                $db = Zynas_Db_Table::getDefaultAdapter();
                $db->beginTransaction();
                PublishHistCreator::createFromKey($control_number, $publish_type)->save();
                $db->commit();
                
                //process of send mail
                try {
                    $can_send_mail = $this->canChangeStatus($do_option, $do_customer_designation, $do_customer_format, $do_base_paper, $file_upload);
                    if (($upload_status_flag && strcmp($file_upload, null) !== 0 && $can_send_mail && strcmp($detail->status, TPublishInfos::STATUS_NOT_ISSUE) === 0)
                        || (strcmp($file_upload, null) !== 0 && strcmp($do_option, TPublishInfos::CK_OFF) !== 0 && strcmp($do_base_paper, TPublishInfos::CK_OFF) !== 0)) {
                        //$to
                        if (strcmp($detail->send_mail, '') !== 0) {
                            $to = $detail->send_mail;
                        } else {
                            $to = MUsers::getInstance()->getUserByUserId($detail->create_user)->email;
                        }
                        //$toName
                        if (isset($detail->send_mail)) {
                            $toName = $detail->send_user_name;
                        } else {
                            $toName = MUsers::getInstance()->getUserByUserId($detail->create_user)->name_jp;
                        }
                        //Cc
                        $cc_mail = array();
                        if ($detail->send_cc_mail1 !== null) {$cc1 = $detail->send_cc_mail1; array_push($cc_mail, $cc1);}
                        if ($detail->send_cc_mail2 !== null) {$cc2 = $detail->send_cc_mail2; array_push($cc_mail, $cc2);}
                        if ($detail->send_cc_mail3 !== null) {$cc3 = $detail->send_cc_mail3; array_push($cc_mail, $cc3);}
                
                        $subject = Zynas_Registry::getConfig()->system->mail->complete_issue->subject;
                        $body = Zynas_Registry::getConfig()->system->mail->complete_issue->template;
                        $fqdn = Zynas_Registry::getConfig()->system->fqdn;
                        $contact = Zynas_Registry::getConfig()->constants->contact_text;
                        $replace = array('name'  => array('{$fqdn}', '{$contact}', '{$control_number}'),
                                         'value' => array($fqdn, $contact, $control_number));
                        Mail::getInstance('complete_issue', $this->view)->sendCc($to, $toName, $cc_mail, $subject, null, $replace);
                    }
                } catch (Exception $e) {
                    throw new Exception('Oops, An error occurred while sending email!', 0, $e);
                }
                
                if ($change_status_flag && $upload_status_flag) {
                    FlashMessenger::addSuccess(E065V);
                }
                Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
                $this->_redirect('/up-down/request?id=' . $id);
            }
        }
    }
    
    /**
     * Download action  
     * 
     */
    public function downloadAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        //Get control_number from URL
        $id = isset($this->_input->id) ? $this->_input->id : '';
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $file_name = ($this->getRequest()->isGet()) ? $this->_input->filename : ''; 
        //Get extension of file download 
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        
        //Set header for each type of files
        $tfile = '';
        $allow_ext = Zynas_Registry::getConfig()->document->type;
        $detail = $this->session->getData(self::SESSION_KEY_DETAIL);
        $yyyymm = str_replace('-' , '', substr($detail->create_date, 0, 7));
        switch (strtolower($file_ext)) {
            case Zynas_Registry::getConfig()->document->pdf:
                header("Content-Type: application/pdf");
                $tfile = APPLICATION_PATH.self::URL_ROOT_UPLOAD.$yyyymm.self::SEPARATOR.$id.self::SEPARATOR.$file_name;
                $tp = pathinfo($tfile);
                break;
            case substr($allow_ext, strpos($allow_ext, 'xls'), 3): 
            case substr($allow_ext, strpos($allow_ext, 'xlsx'), 4):            
                header("Content-Type: application/ms-excel");
                $tfile = APPLICATION_PATH.self::URL_ROOT_UPLOAD.$yyyymm.self::SEPARATOR.$id.self::URL_CUSTOMER_FOLDER.self::SEPARATOR.$file_name;
                $tp = pathinfo($tfile);
                //directly download
                header("Content-disposition: attachment; filename=".$tp['basename'].";");
                break;
            case substr($allow_ext, strpos($allow_ext, 'doc'), 3): 
            case substr($allow_ext, strpos($allow_ext, 'docx'), 4):
                header("Content-Type: application/ms-word");
                $tfile = APPLICATION_PATH.self::URL_ROOT_UPLOAD.$yyyymm.self::SEPARATOR.$id.self::URL_CUSTOMER_FOLDER.self::SEPARATOR.$file_name;
                $tp = pathinfo($tfile);
                //directly download
                header("Content-disposition: attachment; filename=".$tp['basename'].";");
                break;
        }
        
        header("Content-Length: ".filesize($tfile));         
        header('Content-Transfer-Encoding: Binary');
        header('Accept-Ranges: bytes');        
        header('ETag: "'.md5($tfile).'"');
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        
        //open and read file
        if (!empty($tfile)) {
            FileManager::readfileChunked($tfile);
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }
        
    public function handleErrorConfirmRequest() {
                
    }
}
<?php
class DuIssueRequest extends IssueRequest {

    public $customer;
    public $customerFormat;
    public $customerDesignation;
    public $customerDesignationText;
    public $postalInfo;

    public function __construct($customer_ = null, $customerDesignation_ = null,  $customerDesignationText_ = null, $postalInfo_ = null, $customerFormat_ = null, $ckPdf_ = null, $ckPaper_ = null, $customerFile_ = null, $arrayIssueProducts_ = null) {
        parent::__construct($ckPdf_, $ckPaper_, $customerFile_, $arrayIssueProducts_);
        $this->customer = array();
        if(!isset($customer_)) {
            $this->customer['customer_code'] = Zynas_Registry::getConfig() -> customer -> disco_customer_code;
            $this->customer['customer_name'] = MCustomers::getInstance()->getEntryByCode(Zynas_Registry::getConfig() -> customer -> disco_customer_code)->customer_name;
        }
        $this->customerFormat = isset($customerFormat_) ? $customerFormat_ : TPublishInfos::IS_NOT_CUSTOMER_FORMAT;
        $this->customerDesignation = isset($customerDesignation_) ? $customerDesignation_ : TPublishInfos::IS_NOT_CUSTOMER_DESIGNATION;
        $this->postalInfo = isset($postalInfo_) ? $postalInfo_ : array();
        $this->customerDesignationText = isset($customerDesignationText_)?$customerDesignationText:'';
    }

    public function preValidation(array $data) {
        parent::preValidation($data);
        $this->customer['customer_code'] = $data['customer_code'];
        $this->customer['customer_name'] = $data['customer_name'];
        $this->customerFormat = $data['rd_customer_format'];
        $this->customerDesignation = $data['rd_customer_designation'];
        $this->customerDesignationText = $data['txt_customer_designation'];
        if (strcmp($this->ckPaper, '1') === 0) {
            $this->postalInfo = array();
            $this->postalInfo['postal_unit'] = $data['postal_unit'];
            $this->postalInfo['postal_name'] = $data['postal_name'];
        } else {
            $this->postalInfo = array();
            $this->postalInfo['postal_unit'] = Auth_Info::getUser()->unit_name;
            $this->postalInfo['postal_name'] = Auth_Info::getUser()->name_jp;
        }
    }

    public function isCustomerFormat() {
        if(strcmp($this->customerFormat, TPublishInfos::IS_CUSTOMER_FORMAT) === 0)
            return true;
        return false;
    }

    public function isCustomerDesignation() {
        if(strcmp($this->customerDesignation, TPublishInfos::IS_CUSTOMER_DESIGNATION) === 0)
            return true;
        return false;
    }

    public function getCustomerDesignationText() {
        return $this->customerDesignationText;
    }

}

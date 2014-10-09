<?php
class IssueRequest {

    public $publishType;
    public $ckPdf;
    public $ckPaper;
    public $customerFile;
    public $arrayIssueProducts;
    public $canAutoIssue;


    public function __construct($ckPdf_ = null, $ckPaper_ = null, $customerFile_ = null, $arrayIssueProducts_ = null) {
        $this -> ckPdf = $ckPdf_;
        $this -> ckPaper = $ckPaper_;
        $this -> customerFile = $customerFile_;
        if ($customerFile_ == null) {
            $this -> customerFile = array();
        }
        $this -> arrayIssueProducts = $arrayIssueProducts_;
        if ($arrayIssueProducts_ == null) {
            $this -> arrayIssueProducts = array();
        }
    }

    public function preValidation(array $data) {
        function findIndexIssueProduct($field) {
            if (strpos($field, 'index_issue_product_') !== false) {
                return $field;
            }
        }

        $fields = array_filter(array_keys($data), 'findIndexIssueProduct');
        $this -> publishType = $data['publish_type'];
        if(in_array(TPublishInfos::PUBLISH_TYPE_PDF, $this -> publishType)) {
            $this -> ckPdf = '1';
        } else {
            $this -> ckPdf = '0';
        }
        if(in_array(TPublishInfos::PUBLISH_TYPE_PAPER, $this -> publishType)) {
            $this -> ckPaper = '1';
        } else {
            $this -> ckPaper = '0';
        }
        foreach ($fields as $fieldName) {
            $index = ltrim($fieldName, 'index_issue_product_');
            $issueProduct = new IssueProduct($index, $data['select_main_product_category_' . $index], $data['select_product_number_' . $index], $data['text_serial_number_' . $index], $data['text_product_number_view_' . $index], $data['select_model_serial_' . $index], $data['text_product_number_' . $index], $data['select_category_name2_' . $index]);
            //Check that this product is duplicated with any product in array or not
            if(!$this->checkDuplicate($issueProduct, $this->arrayIssueProducts))
                $this->arrayIssueProducts[] = $issueProduct;
        }
    }

    private function checkDuplicate($targetProduct, $arrayProducts) {
        foreach($arrayProducts as $product) {
            if(strcmp($product->mainProductCategory , $targetProduct->mainProductCategory ) === 0
                && strcmp($product->productNumber , $targetProduct->productNumber ) === 0
                && strcmp($product->serialNumber , $targetProduct->serialNumber ) === 0
                && strcmp($product->productNumberView , $targetProduct->productNumberView ) === 0
                && strcmp($product->modelSerial , $targetProduct->modelSerial ) === 0
                && strcmp($product->productNumberTxt , $targetProduct->productNumberTxt ) === 0
                && strcmp($product->categoryName2 , $targetProduct->categoryName2 ) === 0) {
                return true;
            }
        }
        return false;
    }

    public function generateDisplayName() {
        foreach ($this->arrayIssueProducts as $product) {
            $product -> generateDisplayName();
        }
    }

    public function checkAutoIssue() {
        $coverFileName = Zynas_Registry::getConfig() -> template -> cover_page;
        if (!isset($coverFileName)) {
            return false;
        }
        if (!file_exists(TEMPLATE_PATH.'parameterSheet/' . $coverFileName)) {
            return false;
        }
        echo 'Cover page existed';
        foreach ($this->arrayIssueProducts as $product) {
            $result = $product -> canAutoIssue();
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    public function checkNeededSendMail() {
        foreach ($this->arrayIssueProducts as $product) {
            if ($product -> isNeededSendMail()) {
                return true;
            }
        }
        return false;
    }

}

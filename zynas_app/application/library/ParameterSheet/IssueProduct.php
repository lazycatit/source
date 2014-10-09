<?php
class IssueProduct {

    public $index;
    public $mainProductCategory;
    public $productNumber;
    public $serialNumber;
    public $productNumberView;
    public $modelSerial;
    public $productNumberTxt;
    public $categoryName2;

    public $confirmIssueCategoryName;
    public $confirmIssueDisplayName;
    public $productNameView;

    public function __construct($index_ = null, $mainProductCategory_ = null, $productNumber_ = null, $serialNumber_ = null, $productNumberView_ = null, $modelSerial_ = null, $productNumberTxt_ = null, $categoryName2_ = null) {
        $this->mainProductCategory = isset($mainProductCategory_) ? $mainProductCategory_ : '00';
        $this->productNumber = isset($productNumber_) ? $productNumber_ : '00';
        $this->serialNumber = isset($serialNumber_) ? $serialNumber_ : '';
        $this->productNumberView = isset($productNumberView_) ? $productNumberView_ : '';
        $this->modelSerial = isset($modelSerial_) ? $modelSerial_ : '00';
        $this->categoryName2 = isset($categoryName2_) ? $categoryName2_ : '00';
        $this->productNumberTxt = isset($productNumberTxt_) ? $productNumberTxt_ : '';
        $this->index = $index_;
    }

    public function generateDisplayName() {
        $categoryName = MCategorys::getInstance()->getMainProductCategoryName($this->mainProductCategory);
        $this->confirmIssueCategoryName = $categoryName->name1;
        switch ($this -> mainProductCategory) {
            case MCategorys::CATEGORY_DEVICE :
                $product = MProducts::getInstance()->getProduct($this->productNumber);
                $this->confirmIssueDisplayName = $this->productNumber . ' ' . $this->serialNumber;
                $this->productNameView = $product->product_name1 . ' ' . $product->product_number . ' ' . $this->serialNumber;
                break;

            case MCategorys::CATEGORY_SUBDEVICE :
                $product = MProducts::getInstance()->getProductByProductNumberView($this->productNumberView);
                $this->confirmIssueDisplayName = $this->productNumberView;
                $this->productNameView = $product->product_name1 . ' ' . $product->product_number_view;
                break;

            case MCategorys::CATEGORY_PWP :
                switch ($this -> modelSerial) {
                    case '01' :
                        $product = MProducts::getInstance()->getProduct($this->productNumberTxt);
                        if(count($product) > 0) {
                            $category  = MCategorys::getInstance()->getEntry($product->product_category, $product->category, MCategorys::FIELD_CATEGORY);
                            if (!empty($category)) {
                                $this->productNameView = $category->name2 . ' ' . $product->product_number . ' ' . $product->product_name1 . ' ' . $product->product_name2;
                            } else {
                                $this->productNameView = '';
                            }
                        }else {
                            $this->productNameView = '';
                        }
                        $this->confirmIssueDisplayName = $this->productNumberTxt;
                        break;
                    case '02' :
                        $categoryName = MCategorys::getInstance()->getCategoryName2($this->categoryName2);
                        $product = MProducts::getInstance()->getProductByCategory($categoryName->main_category, $categoryName->sub_category);
                        if(count($product) > 0) {
                            $this->productNameView = $categoryName->name2 . ' ' . $categoryName->name1;
                        } else {
                            $this->productNameView = '';
                        }
                        $this->confirmIssueDisplayName = $categoryName->name1;

                    default :
                        break;
                }
            default :
                break;
        }
    }

    public function canAutoIssue() {
        switch ($this -> mainProductCategory) {
            case MCategorys::CATEGORY_PWP :
                return true;

            case MCategorys::CATEGORY_DEVICE :
                return $this->canAutoIssueDevices($this);

            case MCategorys::CATEGORY_SUBDEVICE :
                return $this->canAutoIssueSubDevices($this);

            default :
                return false;
        }
    }

    private function canAutoIssueDevices($issueDevices) {
        if (!empty($issueDevices)) {
            $data = $this->data = MSummaryBeforeOpinions::getInstance()->getEntries($issueDevices->productNumber, $issueDevices->serialNumber);
            if (!empty($data)) {
                if (strcmp($data->result, Db_Table::RESULT_OUTSIDE_LAW) === 0) {//対象外
                    return true;
                }
                //--process array item--//
                $arrayItem = MBeforeOpinions::getInstance()->getEntries($data->product_number, $data->serial_number);
                if (count($arrayItem) > 0) {
                    return $this->checkTemplateToAutoIssue($arrayItem, $issueDevices->productNumber, MCategorys::CATEGORY_DEVICE);
                }
                //--End process array item--/
            }
        }
        return false;
    }

    private function canAutoIssueSubDevices($issueSubDevices) {
        $product = MProducts::getInstance()->getProductByProductNumberView($this->productNumberView);
        if (!empty($product)) {
            $data = $this->data = MSummaryOpinions::getInstance()->getEntries($product->product_number, MCategorys::CATEGORY_SUBDEVICE);
            if (!empty($data)) {
                if (strcmp($data->result, Db_Table::RESULT_OUTSIDE_LAW) === 0) {//対象外
                    return true;
                }
                //--process array item--//
                $arrayItem = MOpinions::getInstance()->getEntries($data->product_number);
                if (count($arrayItem) > 0) {
                    return $this->checkTemplateToAutoIssue($arrayItem, $product->product_number, MCategorys::CATEGORY_SUBDEVICE);
                }
                //--End process array item--/
            }
        }
        return false;
    }//-----End function-----//

    public function isNeededSendMail() {
        switch ($this -> mainProductCategory) {
            case MCategorys::CATEGORY_PWP :
                return false;
                break;
            case MCategorys::CATEGORY_DEVICE :
                $mSummaryBeforeOpinion = MSummaryBeforeOpinions::getInstance()->getEntries($this->productNumber, $this->serialNumber);
                if (strcmp($mSummaryBeforeOpinion->result, Db_Table::RESULT_MATCHED) !== 0) {
                    return false;
                }
                break;
            case MCategorys::CATEGORY_SUBDEVICE :
                $product = MProducts::getInstance()->getProductByProductNumberView($this->productNumberView);
                $mSummaryOpinion = MSummaryOpinions::getInstance()->getEntries($product->product_number, MCategorys::CATEGORY_SUBDEVICE);
                if (strcmp($mSummaryOpinion->result, Db_Table::RESULT_MATCHED) !== 0) {
                    return false;
                }
                break;

            default :
                break;
        }
        return true;
    }

    private function checkTemplateToAutoIssue($arrayItem, $productNumber, $mainProductCategory) {
        foreach ($arrayItem as $item) {
            $template = MTemplates::getInstance()->getEntry($item->item, $item->item_number, $item->type, $item->law_name);
            if (!empty($template)) {
                //--Process template page--//
                if (!empty($template)) {
                    if (!isset($template->file_name) || !isset($template->sheet_name)) {
                        return false;
                    }
                    if (strcmp($template->file_name, '') === 0 || strcmp($template->sheet_name, '') === 0) {
                        return false;
                    }
                    if (!file_exists(TEMPLATE_PATH . 'parameterSheet/' . $template->file_name)) {
                        return false;
                    }
                    $templatePage = MTemplatePages::getInstance()->getEntry($template->item, $template->item_number, $template->type, $template->law_name);
                    if (!empty($templatePage)) {
                        //--Proccess MOpinionValue--/
                        $opinionValue = MOpinionValues::getInstance()->getEntry($templatePage->item, $templatePage->item_number, $template->type, $template->law_name, $productNumber, $mainProductCategory);
                        if (!empty($opinionValue)) {
                            return true;
                            //Finish compare request subdevice
                        }
                        //--End process MOpinionValue--/
                    }
                }
            }
        }
        return false;
    }

}

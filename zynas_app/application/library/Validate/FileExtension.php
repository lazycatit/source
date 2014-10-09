<?php
class Validate_FileExtension extends Zend_Validate_File_Extension {
    
    const NOT_PDF = 'notPdf';
    protected $_messageTemplates = array(
        self::NOT_PDF => E026V,
    );
    
    public function __construct() {
        
    }
    
    public function isValid($value) {
        $status = ''; echo 'abc';
        if (!preg_match('([^\\s]+(\\.(?i)(pdf))$)', $value)) {            
            $this->_error(self::NOT_PDF);
            $status = false;
        } else {
            $status = true;
        }
        return $status;
    }
}
?>
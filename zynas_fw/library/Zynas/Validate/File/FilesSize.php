<?php
class Zynas_Validate_File_FilesSize extends Zend_Validate_File_FilesSize {
    protected $_messageTemplates = array(
        self::TOO_BIG      => 'ファイルの合計が、許可された最大サイズを超えています。',
        self::TOO_SMALL    => '全ファイルの合計は、必須のサイズより小さいです。',
        self::NOT_READABLE => '読み込めないファイルがあります。'
    );
}
?>
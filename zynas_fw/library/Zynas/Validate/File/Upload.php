<?php
class Zynas_Validate_File_Upload extends Zend_Validate_File_Upload {
    protected $_messageTemplates = array(
        self::INI_SIZE       => '定義されたiniサイズを超えています。',
        self::FORM_SIZE      => '定義されたフォームサイズを超えています。',
        self::PARTIAL        => '部分的にアップロードされています。',
        self::NO_FILE        => 'アップロードされていません。',
        self::NO_TMP_DIR     => 'アップロードファイル用のテンポラリディレクトリが見つかりません。',
        self::CANT_WRITE     => 'ファイルに書き込みが出来ません。',
        self::EXTENSION      => 'ファイルをアップロードする際に、エラーが返ってきました。',
        self::ATTACK         => '不正アップロードです。攻撃されている可能性があります。',
        self::FILE_NOT_FOUND => 'ファイルが見つかりません。',
        self::UNKNOWN        => 'ファイルをアップロードする際に、不明なエラーが返ってきました。'
    );
}
?>
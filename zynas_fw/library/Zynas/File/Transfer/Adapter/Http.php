<?php

class Zynas_File_Transfer_Adapter_Http extends Zend_File_Transfer_Adapter_Http {

    const IGNORE_NO_FILE = 'ignoreNoFile';

    public function __construct($options = array()) {
        parent::__construct($options);
        // Zynas_ネームスペースでもPluginLoaderを登録する。
        $types = array(self::VALIDATE, self::FILTER);
        foreach ($types as $type) {
            $prefixSegment = ucfirst(strtolower($type));
            $pathSegment = $prefixSegment;
            $paths = array(
                'Zend_' . $prefixSegment . '_'     => 'Zend/' . $pathSegment . '/',
                'Zend_' . $prefixSegment . '_File' => 'Zend/' . $pathSegment . '/File',
                'Zynas_' . $prefixSegment . '_'     => 'Zynas/' . $pathSegment . '/',
                'Zynas_' . $prefixSegment . '_File' => 'Zynas/' . $pathSegment . '/File'
            );
            $this->setPluginLoader(new Zend_Loader_PluginLoader($paths), $type);
        }
    }

    // !!! side: Zynas_Validate_File_Upload をインスタンス化している以外は親クラスとまったく同じです。
    public function isReceived($files = null) {
        $validate = new Zynas_Validate_File_Upload();
        if (!$validate->isValid($files)) {
            return false;
        }
        return true;
    }
    
    public function setIgnoreNoFileOption($bool) {
        $this->setOptions(array(self::IGNORE_NO_FILE => $bool));
    }

}

?>

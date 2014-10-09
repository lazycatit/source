<?php

class View_Helper_FormImageUploadAjaxConfirm extends Zynas_View_Helper {

    public function formImageUploadAjaxConfirm($fileName, $orgFilePath = null, $width = null) {
        $requestImg = '';

        if (!is_null($orgFilePath) && strcmp($orgFilePath, '') !== 0
        && file_exists(PUB_PATH . $orgFilePath . $fileName)) {
            $requestImg = '<img src="' . $orgFilePath . $fileName . '" ' . (!is_null($width) ? 'style="width:' . $width . ';"' : '') . ' />';
        }
        else {
            $requestImg = '<img src="/image/tmp/filename/' . $fileName . '" ' . (!is_null($width) ? 'style="width:' . $width . ';"' : '') . ' />';
        }

        return $requestImg;
    }

}

?>
<?php

class View_Helper_FormImageUploadAjax extends Zynas_View_Helper {

    public function formImageUploadAjax($name, $fileName = null, $orgFilePath = null, $width = null) {

        $imgId = '__id-' . $name;
        $requestImg = '';
        if (!is_null($fileName) && strcmp($fileName, '') !== 0) {
            if (!is_null($orgFilePath) && strcmp($orgFilePath, '') !== 0
            && file_exists(PUB_PATH . $orgFilePath . $fileName)) {
                $requestImg = '<img src="' . $orgFilePath . $fileName . '" ' . (!is_null($width) ? 'style="width:' . $width . ';"' : '') . '/>';
            }
            else {
                $requestImg = '<img src="/image/tmp/filename/' . $fileName . '" ' . (!is_null($width) ? 'style="width:' . $width . ';"' : '') . '/>';
            }
            $requestImg .= '<a class="delete-image-upload" id="delete-image-upload.' . $imgId . '" href="#">削除する</a>';
        }

        return '<div id="' . $imgId . '-preview">
                    ' . $requestImg . '
                </div>
                ' . $this->getView()->formFile($imgId, array('class' => 'ajax-uploader', 'title' => '/image/do-upload/name/' . $imgId . (!is_null($width) ? '/width/' . $width : ''))) . '
                ' . $this->getView()->formHidden($name, $fileName, array('id' => $imgId . '-uri')) . '
                <p id="' . $imgId . '-error" class="form-error" style="display: none;"></p>';
    }

}

?>
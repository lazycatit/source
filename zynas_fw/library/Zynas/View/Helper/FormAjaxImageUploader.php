<?php

class View_Helper_FormAjaxImageUploader extends Zynas_View_Helper {
    public function formAjaxImageUploader($name, $elementId, $action, $value = null, $title = null, $description = null, $defaultHtml = '') {
        $html = '<input type="hidden" id="ajax-image-upload-action-' . $elementId . '" value="' . $action . (strpos($action, '?') !== false ? '&' : '?') . 'element_id=' . $elementId . '" />';
        $html .= '<input type="hidden" id="ajax-image-upload-input-' . $elementId . '" name="' . $name . '" value="' . $value . '" />';
        $html .= '<div id="ajax-image-upload-image-' . $elementId . '" style="display: inline;">' . ((!is_null($value) && !empty($value)) ? '<img src="/admin/image/tmp/filename/' . $value . '" />' : $defaultHtml) . '</div>';
        $html .= '<a id="ajax-image-upload-trigger-' . $elementId . '" class="ajax-image-upload-trigger" style="cursor: pointer; display: block;">アップロード</a>';
        $html .= '<div id="ajax-image-upload-title-' . $elementId . '" style="display: none;">' . $title . '</div>';
        $html .= '<div id="ajax-image-upload-description-' . $elementId . '" style="display: none;">' . $description . '</div>';
        return $html;
    }
}

?>
<?php

class View_Helper_FormSubmitWithAction extends Zynas_View_Helper {

    const NEW_WINDOW_TARGET = 'newTarget';
    public function formSubmitWithAction($name, $value = null, $action, $form = 'form', $windowOpen = false, $attribs = null) {
        $onclick = '';
        if ($windowOpen) {
            $onclick .= 'window.open(\'\', \'' . self::NEW_WINDOW_TARGET . '\');';
            $onclick .= 'document.' . $form . '.target = \'' . self::NEW_WINDOW_TARGET . '\';';
        }
        else {
            $onclick .= 'document.' . $form . '.target = \'\';';
        }
        if (!empty($action)) {
            $onclick .= 'document.' . $form . '.action = \'' . $action . '\';';
        }
        $onclick .= 'document.' . $form . '.submit();';
        $attribs['onclick'] = $onclick;
        return $this->getView()->formButton($name, $value, $attribs);
    }
}

?>
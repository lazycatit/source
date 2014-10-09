<?php

class View_Helper_FormButtonTo extends Zynas_View_Helper {
    public function formButtonTo($name, $value, $toHref, $attribs = array(), $windowOpen = false) {
        $onclick = '';
        if ($windowOpen) {
            $onclick .= 'var w=window.open(\'' . $toHref . '\', \'_blank\');';
        } else {
            $onclick = 'javascript:window.location.href = \'' . $toHref . '\';';
        }

        $attribs['onclick'] = $onclick;
        return $this->getView()->formButton($name, $value, $attribs);
    }
}

?>
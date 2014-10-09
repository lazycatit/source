<?php

class View_Helper_FormRadioSpan extends Zynas_View_Helper {

    public function formRadioSpan($name, $value, $attribs = array(), $options = array(), $spanClass = '', $span = '<span class="%s">%s&nbsp;%s</span>', $delimiter = '', $isEscape = true) {
        if (!is_array($options)) return null;
        $buttons = array();
        if (!array_key_exists('name', $attribs)) $attribs['name'] = $name;
        if (!array_key_exists('id', $attribs)) $attribs['id'] = $this->_toHtmlAttributeId($attribs['name']);
        $index = 1;
        foreach ($options as $optValue => $optCaption) {
            $buff = array();
            if (is_array($attribs)) foreach ($attribs as $k => $v) $buff[] = htmlspecialchars($k) . '="' . htmlspecialchars($v) . ($k == 'id' ? '-' . $index : '') . '"';
            $buff[] = 'value="' . htmlspecialchars($optValue) . '"';
            if ($optValue == $value) $buff[] = 'checked="checked"';
            if ($isEscape) $optCaption = $this->getView()->escape($optCaption);
            $buttons[] = sprintf($span, $spanClass, '<input type="radio" ' . implode(' ', $buff) . ' />', $optCaption);
            $index++;
        }
        return implode($delimiter, $buttons);
    }

    private function _toHtmlAttributeId($name) {
        $id = htmlspecialchars(trim($name));
        if (substr($id, -2) == '[]') $id = substr($id, 0, strlen($id) - 2);
        if (strstr($id, ']')) {
            $id = trim($id, ']');
            $id = str_replace('][', '-', $id);
            $id = str_replace('[', '-', $id);
        }
        return $id;
    }

}

?>
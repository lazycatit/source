<?php

class View_Helper_FormCheckBoxesSpan extends Zynas_View_Helper {

    public function formCheckboxesSpan($name, $value, $attribs = array(), $options = array(), $spanClass = '', $span = '<span class="%s">%s&nbsp;%s</span>', $delimiter = '', $isEscape = true) {
        if (!is_array($options)) return null;
        if (!is_array($attribs)) $attribs = array();
        if (!is_array($value)) $value = array($value);
        $checkboxes = array();
        if (!array_key_exists('name', $attribs)) $attribs['name'] = $name;
        if (substr($attribs['name'], -2) != '[]') $attribs['name'] .= '[]';
        if (!array_key_exists('id', $attribs)) $attribs['id'] = $this->_toHtmlAttributeId($attribs['name']);
        $index = 0;
        foreach ($options as $optValue => $optCaption) {
            $buff = array();
            if (is_array($attribs)) foreach ($attribs as $k => $v) $buff[] = htmlspecialchars($k) . '="' . htmlspecialchars($v) . ($k == 'id' ? '-' . $index : '') . '"';
            $buff[] = 'value="' . htmlspecialchars($optValue) . '"';
            if (in_array($optValue, $value)) $buff[] = 'checked="checked"';
            if ($isEscape) $optCaption = $this->getView()->escape($optCaption);
            $checkboxes[] = sprintf($span, $spanClass, '<input type="checkbox" ' . implode(' ', $buff) . ' />', $optCaption);
            $index++;
        }
        return implode($delimiter, $checkboxes);
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
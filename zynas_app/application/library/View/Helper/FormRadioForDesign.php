<?php

class View_Helper_FormRadioForDesign extends Zynas_View_Helper {

    public function formRadioForDesign($name, $value, $attribs = array(), $options = array(), $span = '<span>%s<label for="%s">%s</label></span>', $delimiter = '') {
        if (!is_array($options)) return null;
        $buttons = array();
        if (!array_key_exists('name', $attribs)) $attribs['name'] = $name;
        if (!array_key_exists('id', $attribs)) $attribs['id'] = $this->_toHtmlAttributeId($attribs['name']);
        $index = 1;
        foreach ($options as $optValue => $optCaption) {
            $buff = array();
            if (is_array($attribs)) foreach ($attribs as $k => $v) $buff[] = htmlspecialchars($k) . '="' . htmlspecialchars($v) . ($k == 'id' ? '-' . $index : '') . '"';
            $buff[] = 'value="' . htmlspecialchars($optValue) . '"';
            $id = htmlspecialchars($attribs['id']) . '-' . $index;
            if ($optValue == $value) $buff[] = 'checked="checked"';
            $checkboxes[] = sprintf($span, '<input type="radio" ' . implode(' ', $buff) . ' />', $id, htmlspecialchars($optCaption));
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
<?php

class View_Helper_FormCheckBoxes extends Zynas_View_Helper {

    public function formCheckboxes($name, $value, $attribs = array(), $options = array(), $delimiter = '&nbsp;&nbsp;&nbsp;', $label = '<label>%s&nbsp;%s</label>') {
        if (!is_array($value)) $value = array($value);
        $checkboxes = array();
        if (!array_key_exists('name', $attribs)) $attribs['name'] = $name;
        if (substr($attribs['name'], -2) != '[]') $attribs['name'] .= '[]';
        if (!array_key_exists('id', $attribs)) $attribs['id'] = $this->_toHtmlAttributeId($attribs['name']);
        foreach ($options as $optValue => $optCaption) {
            $buff = array();
            foreach ($attribs as $k => $v) $buff[] = htmlspecialchars($k) . '="' . htmlspecialchars($v) . ($k == 'id' ? '-' . $optValue : '') . '"';
            $buff[] = 'value="' . htmlspecialchars($optValue) . '"';
            if (in_array($optValue, $value)) $buff[] = 'checked="checked"';
            $checkboxes[] = sprintf($label, '<input type="checkbox" ' . implode(' ', $buff) . ' />', htmlspecialchars($optCaption));
        }
        return implode($delimiter, $checkboxes);
    }

    // side: 他のformXxx()ヘルパと整合性を保つため、ロジック自体はZend_View_Helper_FormElementから拝借しました。
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
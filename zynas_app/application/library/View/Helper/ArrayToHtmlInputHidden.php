<?php

class View_Helper_ArrayToHtmlInputHidden extends Zynas_View_Helper {

    public function arrayToHtmlInputHidden($ary) {
        return implode("\n", $this->_convert($ary));
    }

    private function _convert($ary, $prefix = '', $level = 1, $hasMoreArrays = false) {
        $tags = array();
        foreach ($ary as $k => $v) {
            if (is_array($v)) {
                $tags = array_merge($tags, $this->_convert($v, empty($prefix) ? $k : $prefix . '[' . $k . ']', $level + 1, $this->_hasArray($v)));
            }
            else {
                $tags[] = $this->getView()->formHidden($level == 1 ? $k : $prefix . ($hasMoreArrays || !$this->_isReallyArray($ary) ? '[' . $k . ']' : '[]'), $v);
            }
        }
        return $tags;
    }

    private function _hasArray($ary) {
        foreach ($ary as $var) if (is_array($var)) return true;
        return false;
    }

    private function _isReallyArray($ary) {
        if (!is_array($ary)) return false;
        $iMax = count($ary);
        $keys = array_keys($ary);
        for ($i = 0; $i < $iMax; $i++) if (!is_integer($keys[$i]) || $keys[$i] != $i) return false;
        return true;
    }

}

?>
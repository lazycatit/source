<?php
class View_Helper_FormLabelError extends Zynas_View_Helper
{
    public function formLabelError($name, $value = null, array $attribs = null)
    {
        // enabled; display label
        $xhtml = '<label style="color:#f00;" id='
                . $name
                . '>' . $value . '</label>';

        return $xhtml;
    }
}

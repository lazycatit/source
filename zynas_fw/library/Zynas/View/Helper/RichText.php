<?php

class View_Helper_RichText extends Zynas_View_Helper {
    public function richText($name, $value, $attrib, $options = array()) {
		$html ='<textarea name="' . $name . '" cols="' . $attrib['cols'] . '" rows="' . $attrib['rows'] . '"  id="rte-' . $name . '" class="rte-' . $name . '">' . $value . '</textarea>';
		foreach($options as $k => $v) {
    		$html .= '<input type="hidden" name="' . $k . '" id="' . $k . '" value="' . $v . '">';		
		}
		$html .= '<input type="hidden" name="element_id" id="element_id" value="1">';		
        if(!$options['upload_path']) $html .= '<input type="hidden" name="upload_path" id="upload_path" value="/_var/module/tmp/">';
		$js = '
		<script type="text/javascript">
			var arr = $(\'.rte-' . $name . '\').rte({';
		if($options['css']) $js .= 'css: ["/_var/module/' . $options['css'] . '"],';
		$js .= 'controls_rte: rte_toolbar,			
				controls_html: html_toolbar
			});
		</script>';
        return $html . $js;
    }
}

?>
<?php

class Zynas_Exception extends Zend_Exception {
	private $_errors;
	
	public function getErrors($key = null) {
		return !is_null($key) ? $this->_errors[$key] : $this->_errors;
	}
	
	public function setErrors($errors) {
		$this->_errors = $errors;
	}
}

?>
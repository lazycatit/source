<?php

class Zynas_Session_Namespace extends Zend_Session_Namespace {
	
	const KEY_AUTH = 'auth';
	
	public static function factory($namespace) {
		return new Zynas_Session_Namespace($namespace);
	}
	
}

?>
<?php

class Zynas_Validate extends Zend_Validate {
    // side: Zend_Validateのコピペ。breakChainOnFailureの初期値をtrueに変更しています。
    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = true)
    {
        $this->_validators[] = array(
            'instance' => $validator,
            'breakChainOnFailure' => (boolean) $breakChainOnFailure
            );
        return $this;
    }
    // side: 基本的にbreakするので、単体のエラーメッセージを返すメソッドを定義する。
    public function getMessage() {
        return current($this->getMessages());
    }
}

?>
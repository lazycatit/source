<?php

class Zynas_Filter_Input extends Zend_Filter_Input {
    const KEY_FILTER = 'filter';
    const KEY_VALIDATE = 'validate';
    public function __construct($filterRules, $validatorRules, $data = array(), $options = array()) {
        $filterRules = array_merge(array('*' => new Zynas_Filter_StringTrim()), $filterRules);
        $defaultOptions = array(
            self::ALLOW_EMPTY => false,
            self::BREAK_CHAIN => true,
            self::ESCAPE_FILTER => 'StringTrim',
            self::MISSING_MESSAGE => '入力必須です。',
            self::NOT_EMPTY_MESSAGE => '入力必須です。',
            self::PRESENCE => self::PRESENCE_REQUIRED
        );
        parent::__construct(array('*' => new Zynas_Filter_StringTrim()), $validatorRules, $data, array_merge($defaultOptions, $options));
        $this->addFilterPrefixPath('Zynas_Filter_', 'Zynas/Filter/');
        $this->addValidatorPrefixPath('Zynas_Validate_', 'Zynas/Validate/');
    }
}

?>
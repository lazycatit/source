<?php

class Validate_EmailAddressNg extends Zend_Validate_Abstract {

    const NG_EMAIL = 'ngEmail';

    private $_ngDomains = array(
        'renraku.in',
        'shibuya-center.com',
        'meltmail.com',
        'tittbit.in',
        'supermailer.jp',
        'tempthe.net'
        );

        public function isValid($value) {
            $this->_messageTemplates[self::NG_EMAIL] = 'このメールアドレスでは登録できません。恐れ入りますが他のメールアドレスでご登録下さい。';

            list($id, $domain) = explode('@', $value);
            foreach ($this->_ngDomains as $ngDomain) {
                if (strpos($domain, $ngDomain) !== false) {
                    $this->_error(self::NG_EMAIL);
                    return false;
                }
            }
            return true;
        }

}
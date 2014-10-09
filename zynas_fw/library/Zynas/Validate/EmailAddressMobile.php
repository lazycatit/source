<?php

class Zynas_Validate_EmailAddressMobile extends Zynas_Validate_EmailAddress {

    const NOT_EMAIL_MOBILE = 'notEmailMobile';

    private $_mobileDomains = array(
        'docomo.ne.jp', 'softbank.ne.jp', 'disney.ne.jp', 'd.vodafone.ne.jp',
        'h.vodafone.ne.jp', 't.vodafone.ne.jp', 'c.vodafone.ne.jp',
        'r.vodafone.ne.jp', 'k.vodafone.ne.jp', 'n.vodafone.ne.jp',
        's.vodafone.ne.jp', 'q.vodafone.ne.jp', 'jp-d.ne.jp', 'jp-h.ne.jp',
        'jp-t.ne.jp', 'jp-c.ne.jp', 'jp-r.ne.jp', 'jp-k.ne.jp', 'jp-n.ne.jp',
        'jp-s.ne.jp', 'jp-q.ne.jp', 'ezweb.ne.jp', 'ido.ne.jp', 'ezweb.ne.jp',
        'ezweb.ne.jp', 'sky.tkk.ne.jp', 'sky.tkc.ne.jp', 'sky.tu -ka.ne.jp',
        'pdx.ne.jp', 'di.pdx.ne.jp', 'dj.pdx.ne.jp', 'dk.pdx.ne.jp',
        'wm.pdx.ne.jp', 'willcom.com', 'emnet.ne.jp'
    );

    public function isValid($value) {

        if (!parent::isValid($value)) return false;

        // side: クラス定義で宣言してしまうと親のエラーメッセージ群が上書きされてしまうので必要なメッセージだけこちらで追加します。
        $this->_messageTemplates[self::NOT_EMAIL_MOBILE] = 'モバイルメールアドレスではありません。';

        list($id, $domain) = explode('@', $value);
        if (!in_array($domain, $this->_mobileDomains) && !preg_match('/[a-z]+\.biz\.ezweb\.ne\.jp/', $domain)) {
            $this->_error(self::NOT_EMAIL_MOBILE);
            return false;
        }
        return true;
    }
}

?>
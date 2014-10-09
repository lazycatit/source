<?php

// Zend_Mailをデフォルトで日本語に易しくなるようにオーバーライドしています。
// 文字コードの変換を行い、長い件名も適切に扱うことができるようにしました。

class Zynas_Mail extends Zend_Mail {

    const ENCODING_JIS = 'ISO-2022-JP';
    const ENCODING_UTF8 = 'UTF-8';

    // デフォルト文字コードをSJISに置き換え、ヘッダの文字コードもここで指定しています。
    public function __construct($charset = self::ENCODING_JIS) {
        $this->_charset = $charset;
        $this->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
    }

    // 文字コードの変換を行います。
    public function setBodyText($txt, $charset = null, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE) {
        $txt = mb_convert_encoding($txt, self::ENCODING_JIS, self::ENCODING_UTF8);
        return parent::setBodyText($txt, null, Zend_Mime::ENCODING_7BIT);
    }

    // 文字コードの指定を行います。
    public function setBodyHtml($txt, $charset = null, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE) {
        return parent::setBodyHtml($txt, self::ENCODING_UTF8, Zend_Mime::ENCODING_QUOTEDPRINTABLE);
    }

    // ヘッダ内に使用される日本語のテキストを文字コード変換、base64エンコード、チャック分けをしています。
    private static function _encodeHeaderText($txt) {
        $maxCharsMime = 74;
        if (empty($txt) || (strlen(bin2hex($txt)) / 2 == mb_strlen($txt, self::ENCODING_UTF8))) {
            return trim(chunk_split($txt, $maxCharsMime, "\r\n"));
        }
        $txt = mb_convert_encoding(mb_convert_kana($txt, 'KV', self::ENCODING_UTF8), self::ENCODING_JIS, self::ENCODING_UTF8);
        $start = '=?' . self::ENCODING_JIS . '?B?';
        $end = '?=';
        $spacer = $end . chr(13) . chr(10) . chr(9) . $start;
        $length = $maxCharsMime - strlen($start) - strlen($end);
        $pointer = 1;
        $posStart = 0;
        $line = null;
        $_ret = array();
        $max = mb_strlen($txt ,self::ENCODING_JIS);
        while($pointer <= $max) {
            $line = mb_substr($txt, $posStart, $pointer - $posStart, self::ENCODING_JIS);
            $bs64len = strlen(bin2hex(base64_encode($line))) / 2;
            if ((0 !== count($_ret) && $bs64len <= $length) || (0 === count($_ret) && $bs64len <= ($length - 9))) {
                $pointer++;
            }
            else {
                $_ret[] = base64_encode($line);
                $posStart = $pointer;
            }
        }
        if (strlen(trim($line)) > 0) $_ret[] = base64_encode($line);
        return preg_replace(array('/\0/is','/\r[^\n]/is'), '', $start . implode($spacer, $_ret) . $end);
    }

    public function setSubject($subject) {
        return parent::setSubject(self::_encodeHeaderText($subject));
    }

    public function addTo($email, $name = '') {
        return parent::addTo($email, self::_encodeHeaderText($name));
    }

    public function addCc($email, $name = '') {
        return parent::addCc($email, self::_encodeHeaderText($name));
    }

    public function setReplyTo($email, $name = '') {
        return parent::setReplyTo($email, self::_encodeHeaderText($name));
    }

    public function setFrom($email, $name = '') {
        return parent::setFrom($email, self::_encodeHeaderText($name));
    }

    public static function setDefaultReplyTo($email, $name = '') {
        return Zend_Mail::setDefaultReplyTo($email, self::_encodeHeaderText($name));
    }

    public static function setDefaultFrom($email, $name = '') {
        return Zend_Mail::setDefaultFrom($email, self::_encodeHeaderText($name));
    }
}

?>
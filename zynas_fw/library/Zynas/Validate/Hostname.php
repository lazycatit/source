<?php
class Zynas_Validate_Hostname extends Zend_Validate_Hostname {
    protected $_messageTemplates = array(
        self::IP_ADDRESS_NOT_ALLOWED  => 'IPアドレスは許可されていません。',
        self::UNKNOWN_TLD             => 'ホスト名が、TLDに対してマッチしませんでした。',
        self::INVALID_DASH            => 'ダッシュ (-) がホスト名の不適切な場所にあります。',
        self::INVALID_HOSTNAME_SCHEMA => 'ホスト名が、TLD %tld% のホスト名スキーマに対してマッチしませんでした。',
        self::UNDECIPHERABLE_TLD      => 'ホスト名を、TLD パート から抜粋することが出来ませんでした。',
        self::INVALID_HOSTNAME        => 'DNS ホスト名が期待する構造にマッチしませんでした。',
        self::INVALID_LOCAL_NAME      => 'ローカルネットワーク名にありませんでした。',
        self::LOCAL_NAME_NOT_ALLOWED  => 'ローカルネットワーク名にありますが、許可されていません。'
    );
}
?>
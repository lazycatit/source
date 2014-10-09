<?php

class Auth_Info {

    /**
     * ログイン者情報を取得
     * @return MUser
     */
    public static function getUser() {
        return Auth_User::getInstance()->getUser();
    }

    /**
     * ログイン時間を取得
     * @return MUser
     */
    public static function getLoginTime() {
        return Auth_User::getInstance()->getLoginTime();
    }

    /**
     * ログイン状態確認
     * @return boolean
     */
    public static function isLoggedUser() {
        return Auth_User::getInstance()->isLogged();
    }

}

?>